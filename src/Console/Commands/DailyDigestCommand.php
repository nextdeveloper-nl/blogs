<?php

namespace NextDeveloper\Blogs\Console\Commands;

use Illuminate\Console\Command;
use NextDeveloper\Blogs\Database\Models\Posts;
use NextDeveloper\Blogs\Envelopes\DailyDigestNotification;
use NextDeveloper\Communication\Helpers\Communicate;
use NextDeveloper\IAM\Database\Models\Users;
use NextDeveloper\Communication\Services\EmailsService;
use NextDeveloper\IAM\Helpers\UserHelper;

class DailyDigestCommand extends Command
{
    protected $signature = 'nextdeveloper:daily-digest';

    protected $description = 'Send daily digest to all users';

    /**
     * The number of random posts to include in digest
     */
    protected int $randomPostsCount = 5;

    public function __construct()
    {
        parent::__construct();

        UserHelper::setUserById(config('leo.current_user_id'));
        UserHelper::setCurrentAccountById(config('leo.current_account_id'));
    }

    public function handle(): void
    {
        try {
            $this->info('Fetching latest posts...');

            // Get latest posts
            $posts = Posts::withoutGlobalScopes()
                ->where('is_active', true)
                ->where('is_draft', false)
                ->where('created_at', '>=', now()->subDay())
                ->get();

            // get random posts
            $randomPosts = Posts::withoutGlobalScopes()
                ->where('is_active', true)
                ->where('is_draft', false)
                ->where('created_at', '<', now()->subDay())
                ->inRandomOrder()
                ->limit($this->randomPostsCount)
                ->get();

            if ($posts->isEmpty() && $randomPosts->isEmpty()) {
                $this->warn('No posts available for digest. Skipping email sending.');
                return;
            }

            $this->info('Sending daily digest to users...');

            $processedUsers = 0;

            // Get all users
            Users::withoutGlobalScopes()
                ->where('is_active', true)
                ->chunk(100, function ($users) use ($posts, $randomPosts, &$processedUsers) {
                    try {
                        $users->each(function($user) use ($posts, $randomPosts) {
                            try {
                                $this->info('Sending digest to user: ' . $user->email);

                                $text = //i18n::t(
                                    "Hey {$user->name}!\n\nWe have some exciting blog posts for you today. Check out our latest articles and some interesting picks we've selected just for you.";
                                $text = nl2br($text);

                                $subject = 'Your Daily Blog Digest';

                                // render the view
                                $view = view('Blogs::emails.html.daily-digest-notification', [
                                    'posts' => $posts,
                                    'randomPosts' => $randomPosts,
                                    'user' => $user,
                                    'subject' => $subject,
                                    'text' => $text,
                                ])->render();

                                // prepare data
                                $data = [
                                    'body' => $view,
                                    'subject' => $subject,
                                    'to' => [$user->email],
                                    'from_email_address' => config('communication.from.email'),
                                    'deliver_at' => now()->setTime(9, 0),
                                    'is_marketing_email' => true,
                                ];

                                // save to database
                                EmailsService::create($data);

                                $this->info('Digest sent to user: ' . $user->email);
                            } catch (\Exception $e) {
                                \Log::error('Failed to send digest to user: ' . $user->email, [
                                    'error' => $e->getMessage(),
                                    'user_id' => $user->id
                                ]);
                            }
                        });

                        $processedUsers += $users->count();
                        $this->info("Processed {$processedUsers} users...");
                    } catch (\Exception $e) {
                        \Log::error('Failed to process user chunk', [
                            'error' => $e->getMessage()
                        ]);
                    }
                });

            $this->info("Daily digest sent successfully to {$processedUsers} users");
        } catch (\Exception $e) {
            $this->error('Failed to send daily digest: ' . $e->getMessage());
            \Log::error('Daily digest command failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
