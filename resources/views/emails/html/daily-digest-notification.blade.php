<!doctype html>
<html>
<head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>{!! $subject !!}</title>
    <style>
        /* -------------------------------------
            GLOBAL RESETS
        ------------------------------------- */
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }
        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }

        /* -------------------------------------
            BODY & CONTAINER
        ------------------------------------- */
        .body {
            background-color: #f6f6f6;
            width: 100%;
        }

        .container {
            display: block;
            margin: 0 auto !important;
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }

        /* -------------------------------------
            HEADER, FOOTER, MAIN
        ------------------------------------- */
        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }
        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }

        /* -------------------------------------
            TYPOGRAPHY
        ------------------------------------- */
        h1, h2, h3, h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p, ul, ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }

        .post-item {
            margin-bottom: 30px;
            padding: 20px;
            border: 1px solid #eee;
            border-radius: 8px;
            background: #fff;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        }

        .post-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 15px;
        }

        .post-image {
            width: 120px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            margin-right: 15px;
        }

        .post-meta {
            color: #666;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .post-title {
            color: #2c3e50;
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 10px;
            text-decoration: none;
        }

        .post-title a {
            color: inherit;
            text-decoration: none;
        }

        .post-title a:hover {
            color: #3498db;
        }

        .post-excerpt {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .post-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 12px;
            color: #666;
        }

        .post-stats {
            display: flex;
            gap: 15px;
        }

        .post-stat {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .post-tags {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .post-tag {
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            color: #495057;
            font-size: 11px;
        }

        .section-title {
            color: #2c3e50;
            font-size: 24px;
            margin: 30px 0 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
        }

        .affiliate-section {
            background: #f8f9fa;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            padding: 20px;
            margin: 30px 0;
            text-align: center;
        }

        .affiliate-title {
            color: #2c3e50;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .affiliate-text {
            color: #495057;
            margin-bottom: 20px;
        }

        .affiliate-button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff !important;
            padding: 12px 25px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
        }

        .affiliate-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body class="">
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main">
                    <!-- START MAIN CONTENT AREA -->
                    <tr>
                        <td class="wrapper">
                            <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td class="content-block powered-by">
                                        <img src="@php echo config('communication.labeling.logo') @endphp" alt="Logo" />
                                        <hr />
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        {!! $text !!}

                                        @if($posts->isNotEmpty())
                                            <h2 class="section-title">Latest Posts</h2>
                                            @foreach($posts as $post)
                                                <div class="post-item">
                                                    <div class="post-header">
                                                        @if($post->header_image)
                                                            <img src="{{ $post->header_image }}" alt="{{ $post->title }}" class="post-image">
                                                        @endif
                                                        <div>
                                                            <div class="post-meta">
                                                                {{ $post->created_at->format('F j, Y') }}
                                                                @if($post->is_pinned)
                                                                    · <span style="color: #e74c3c;">Featured</span>
                                                                @endif
                                                            </div>
                                                            <h3 class="post-title">
                                                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                                                            </h3>
                                                        </div>
                                                    </div>

                                                    @if($post->abstract)
                                                        <p class="post-excerpt">{{ $post->abstract }}</p>
                                                    @endif

                                                    <div class="post-footer">
                                                        <div class="post-stats">
                                                            <span class="post-stat">👁 {{ $post->read_count }} reads</span>
                                                            <span class="post-stat">💬 {{ $post->reply_count }} replies</span>
                                                            @if($post->bonus_points)
                                                                <span class="post-stat">🏆 {{ $post->bonus_points }} points</span>
                                                            @endif
                                                        </div>

                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        @if($randomPosts->isNotEmpty())
                                            <h2 class="section-title">You Might Also Like</h2>
                                            @foreach($randomPosts as $post)
                                                <div class="post-item">
                                                    <div class="post-header">
                                                        @if($post->header_image)
                                                            <img src="{{ $post->header_image }}" alt="{{ $post->title }}" class="post-image">
                                                        @endif
                                                        <div>
                                                            <div class="post-meta">
                                                                {{ $post->created_at->format('F j, Y') }}
                                                                @if($post->is_pinned)
                                                                    · <span style="color: #e74c3c;">Featured</span>
                                                                @endif
                                                            </div>
                                                            <h3 class="post-title">
                                                                <a href="{{ $post->url }}">{{ $post->title }}</a>
                                                            </h3>
                                                        </div>
                                                    </div>

                                                    @if($post->abstract)
                                                        <p class="post-excerpt">{{ $post->abstract }}</p>
                                                    @endif

                                                    <div class="post-footer">
                                                        <div class="post-stats">
                                                            <span class="post-stat">👁 {{ $post->read_count }} reads</span>
                                                            <span class="post-stat">💬 {{ $post->reply_count }} replies</span>
                                                            @if($post->bonus_points)
                                                                <span class="post-stat">🏆 {{ $post->bonus_points }} points</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @endif

                                        <div class="affiliate-section">
                                            <h2 class="affiliate-title">Join Our Affiliate Program</h2>
                                            <p class="affiliate-text">Want to earn while sharing great content? Join our affiliate program and earn commissions for every new subscriber you bring!</p>
                                            <a href="{{ config('leo.urls.affiliate_program') }}" class="affiliate-button">Start Earning Today</a>
                                        </div>

                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- END CENTERED WHITE CONTAINER -->

                <!-- START FOOTER -->
                <div class="footer">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                        <tr>
                            <td class="content-block">
                                <br>@php echo config('communication.labeling.unsubscribe') @endphp
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- END FOOTER -->
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>
