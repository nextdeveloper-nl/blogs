-- PostgreSQL

CREATE TABLE blog_posts (
    id                  bigint NOT NULL DEFAULT nextval('blog_posts_id_seq'::regclass),
    uuid                uuid DEFAULT gen_random_uuid(), -- [ro]
    slug                text,
    title               text NOT NULL,
    body                text NOT NULL, -- [ui:markdown]
    header_image        text, -- [ui:image]
    meta_title          text,
    meta_description    text,
    meta_keywords       text,
    reply_count         integer DEFAULT 0, -- [ro]
    read_count          bigint NOT NULL DEFAULT 0, -- [ro]
    bonus_points        integer DEFAULT 0, -- [ro]
    is_active           boolean DEFAULT true,
    is_locked           boolean DEFAULT false,
    is_pinned           boolean DEFAULT false,
    is_draft            boolean DEFAULT true,
    is_markdown         boolean DEFAULT false,
    tags                text[] NOT NULL DEFAULT '{}'::text[],
    iam_account_id      bigint, -- [ro]
    iam_user_id         bigint, -- [ro]
    common_category_id  bigint NOT NULL,
    common_domain_id    bigint NOT NULL,
    created_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          timestamp with time zone NOT NULL DEFAULT CURRENT_TIMESTAMP,
    deleted_at          timestamp with time zone,
    abstract            text,
    alternates          json DEFAULT '[]'::json,
    alternate_of        bigint,
    locale              text DEFAULT 'tr'::text,
    blog_account_id     bigint,
    faqs                json,
    CONSTRAINT blog_posts_pkey PRIMARY KEY (id)
);
