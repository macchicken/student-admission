DROP TABLE admin_comment;
DROP TABLE phd_admissions;
DROP TABLE uploaded_documents;
DROP TABLE admission_supervisors;

DROP SEQUENCE admin_comment_sq;
DROP SEQUENCE admission_id;
DROP SEQUENCE document_id_sq;



-- Name: admin_comment; Type: TABLE; Schema: public; Owner: -; Tablespace: --
CREATE TABLE "admin_comment" (
    admin_comment_id integer,
    "date_added" timestamp with time zone,
    "Comment" text,
    "dealt_with" bool,
    "admission_id" integer DEFAULT 0 NOT NULL,
    "sender" character varying(20)

); 

-- Name: phd_admissions; Type: TABLE; Schema: public; Owner: -; Tablespace:--
CREATE TABLE "phd_admissions" (
    admission_id integer DEFAULT 0 NOT NULL,
    "registry" integer,
    "surname" character varying(60),
    "forenames" character varying(60),
    "Origin" character varying(10),
    "origin_note" text,
    "possible_funding" character varying(10),
    "funding_note" text,
    "status" character varying(20),
    "status_note" text,
    "time_added" timestamp with time zone,
    "time_modified" timestamp with time zone,
    "research_subject" character varying(100),
    "admin_tutor_comment" text
); 

-- Name: uploaded_documents; Type: TABLE; Schema: public; Owner: -; Tablespace:--
CREATE TABLE "uploaded_documents" (
    "document_id" integer,
    "document_type" character varying(30),
    admission_id integer DEFAULT 0 NOT NULL,
    "time_added" timestamp with time zone,
    "time_changed" timestamp with time zone,
    "hidden" bool,
    "reference" text
);

-- Name: admission_supervisors; Type: TABLE; Schema: public; Owner: -; Tablespace:--
CREATE TABLE "admission_supervisors" (
    admission_id integer DEFAULT 0 NOT NULL,
    "academic_login" character varying(20),
    "supervisor_flag" character varying(20),
    "Recommendation" character varying(50),
    "comment_and_justification" text,
    "acceptance_condition" text,
    "other_supervisor" text,
    "initial_email_send" bool,
    "created_date" timestamp with time zone,
    "modified_date" timestamp with time zone,
    "viewed" bool
);

CREATE SEQUENCE admin_comment_sq;
CREATE SEQUENCE admission_id;
CREATE SEQUENCE document_id_sq;

