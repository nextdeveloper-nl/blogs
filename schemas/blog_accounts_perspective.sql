-- PostgreSQL
-- VIEW (read-only; re-run this file with CREATE OR REPLACE VIEW whenever the SELECT needs to change)

CREATE OR REPLACE VIEW blog_accounts_perspective AS
SELECT ba.id,
    ba.uuid,
    cd.name,
    cd.is_active,
    cd.tags,
    ba.common_domain_id,
    ba.common_language_id,
    ba.limits,
    ba.is_suspended,
    ba.is_auto_translate_enabled,
    ba.alternate,
    ba.created_at,
    ba.updated_at,
    ba.deleted_at
   FROM blog_accounts ba
     JOIN common_domains cd ON ba.common_domain_id = cd.id;
