-- -------------------------------------------------------------
-- TablePlus 5.6.6(520)
--
-- https://tableplus.com/
--
-- Database: app
-- Generation Time: 2024-10-25 14:11:19.8630
-- -------------------------------------------------------------


DROP TABLE IF EXISTS "public"."doctrine_migration_versions";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Table Definition
CREATE TABLE "public"."doctrine_migration_versions" (
    "version" varchar(191) NOT NULL,
    "executed_at" timestamp(0) DEFAULT NULL::timestamp without time zone,
    "execution_time" int4,
    PRIMARY KEY ("version")
);

DROP TABLE IF EXISTS "public"."messenger_messages";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS messenger_messages_id_seq;

-- Table Definition
CREATE TABLE "public"."messenger_messages" (
    "id" int8 NOT NULL DEFAULT nextval('messenger_messages_id_seq'::regclass),
    "body" text NOT NULL,
    "headers" text NOT NULL,
    "queue_name" varchar(190) NOT NULL,
    "created_at" timestamp(0) NOT NULL,
    "available_at" timestamp(0) NOT NULL,
    "delivered_at" timestamp(0) DEFAULT NULL::timestamp without time zone,
    PRIMARY KEY ("id")
);

-- Column Comment
COMMENT ON COLUMN "public"."messenger_messages"."created_at" IS '(DC2Type:datetime_immutable)';
COMMENT ON COLUMN "public"."messenger_messages"."available_at" IS '(DC2Type:datetime_immutable)';
COMMENT ON COLUMN "public"."messenger_messages"."delivered_at" IS '(DC2Type:datetime_immutable)';

DROP TABLE IF EXISTS "public"."pokemon";
-- This script only contains the table creation statements and does not fully represent the table in the database. It's still missing: indices, triggers. Do not use it as a backup.

-- Sequence and defined type
CREATE SEQUENCE IF NOT EXISTS pokemon_id_seq;

-- Table Definition
CREATE TABLE "public"."pokemon" (
    "id" int4 NOT NULL DEFAULT nextval('pokemon_id_seq'::regclass),
    "name" varchar(255) NOT NULL,
    "type" varchar(255) NOT NULL,
    PRIMARY KEY ("id")
);

INSERT INTO "public"."doctrine_migration_versions" ("version", "executed_at", "execution_time") VALUES
('DoctrineMigrations\Version20241025120035', '2024-10-25 12:00:46', 29);

INSERT INTO "public"."pokemon" ("id", "name", "type") VALUES
(1, 'Tiploufe', 'Eau'),
(2, 'PIKACHU', 'Electrique'),
(3, 'Moi', 'Flemme');

