-- PostgreSQL
-- The blog accounts that are used to manage the blog content.

CREATE TABLE blog_accounts (
    id                         bigint NOT NULL DEFAULT nextval('blog_accounts_id_seq'::regclass),
    uuid                       uuid NOT NULL DEFAULT gen_random_uuid(),
    limits                     text[],
    is_suspended               boolean DEFAULT false,
    created_at                 timestamp with time zone DEFAULT now(),
    updated_at                 timestamp with time zone DEFAULT now(),
    deleted_at                 timestamp with time zone,
    alternate                  json NOT NULL DEFAULT '{"blog_account_ids": []}'::jsonb, -- The alternate translation configuration for the blog content.
    common_domain_id           bigint NOT NULL,
    is_auto_translate_enabled  boolean DEFAULT true, -- The flag to enable automatic translation of the blog content.
    common_language_id         bigint,
    iam_account_id             bigint,
    CONSTRAINT blog_accounts_pkey PRIMARY KEY (id),
    CONSTRAINT blog_accounts_common_domain_id_key UNIQUE (common_domain_id)
);
