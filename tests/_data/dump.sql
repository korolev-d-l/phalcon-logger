CREATE TABLE logs (
    id integer NOT NULL,
    entity character varying(200) NOT NULL,
    "entityId" integer NOT NULL,
    date timestamp without time zone NOT NULL,
    "userId" integer NOT NULL,
    action character varying(40) NOT NULL,
    diff json NOT NULL
);

CREATE SEQUENCE logs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;

ALTER SEQUENCE logs_id_seq OWNED BY logs.id;
ALTER TABLE ONLY logs ALTER COLUMN id SET DEFAULT nextval('logs_id_seq'::regclass);
ALTER TABLE ONLY logs
    ADD CONSTRAINT unique_id PRIMARY KEY (id);
CREATE INDEX idx_action ON logs USING btree (action);
CREATE INDEX idx_date ON logs USING btree (date);
CREATE INDEX idx_entity ON logs USING btree (entity);
CREATE INDEX idx_entity_id ON logs USING btree ("entityId");
CREATE INDEX idx_user_id ON logs USING btree ("userId");
