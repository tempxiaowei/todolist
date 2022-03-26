/* 需要在 SQLite 中显式启用外键约束 */
PRAGMA foreign_keys =ON;

DROP TABLE IF EXISTS todo;

CREATE TABLE todo (
	id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
	todotxt VARCHAR NOT NULL,
        okorno VARCHAR NOT NULL,
	created_at VARCHAR NOT NULL
);

