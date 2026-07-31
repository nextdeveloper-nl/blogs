-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW blog_posts_perspective AS
SELECT id,
    uuid,
    slug,
    title,
    body,
    abstract,
    header_image,
    meta_title,
    meta_description,
    meta_keywords,
    reply_count,
    read_count,
    bonus_points,
    is_active,
    is_locked,
    is_pinned,
    is_draft,
    is_markdown,
    tags,
    iam_account_id,
    iam_user_id,
    common_domain_id,
    locale,
    alternates,
    alternate_of,
    faqs,
    ( SELECT c_iu.fullname
           FROM iam_users c_iu
          WHERE c_iu.id = bp.iam_user_id) AS author,
    ( SELECT c_ia.name
           FROM iam_accounts c_ia
          WHERE c_ia.id = bp.iam_account_id) AS team,
    common_category_id,
    ( SELECT c_cc.name
           FROM common_categories c_cc
          WHERE c_cc.id = bp.common_category_id) AS category,
    ( SELECT c_cd.name
           FROM common_domains c_cd
          WHERE c_cd.id = bp.common_domain_id) AS domain_name,
    created_at,
    updated_at,
    deleted_at
   FROM blog_posts bp
  ORDER BY created_at DESC;
