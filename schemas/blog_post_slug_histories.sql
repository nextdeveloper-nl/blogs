-- PostgreSQL

CREATE TABLE blog_post_slug_histories (
    id              bigint NOT NULL DEFAULT nextval('blog_post_slug_histories_id_seq'::regclass),
    uuid            uuid NOT NULL DEFAULT gen_random_uuid(),
    blog_post_id    bigint NOT NULL,
    slug            text NOT NULL,
    iam_account_id  bigint,
    iam_user_id     bigint,
    created_at      timestamp without time zone,
    updated_at      timestamp without time zone,
    deleted_at      timestamp without time zone,
    CONSTRAINT blog_post_slug_histories_blog_post_id_fkey FOREIGN KEY (blog_post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
    CONSTRAINT blog_post_slug_histories_pkey PRIMARY KEY (id),
    CONSTRAINT blog_post_slug_histories_uuid_key UNIQUE (uuid)
);
