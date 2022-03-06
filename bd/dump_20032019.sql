--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- Name: id_abono_empleado_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_abono_empleado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_abono_empleado_seq OWNER TO darm;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: abono_empleado; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.abono_empleado (
    id_abono_empleado integer DEFAULT nextval('public.id_abono_empleado_seq'::regclass) NOT NULL,
    empleado_cedula integer NOT NULL,
    fecha character varying(10) NOT NULL,
    monto double precision NOT NULL,
    fecha_num integer NOT NULL,
    login character varying(255) NOT NULL
);


ALTER TABLE public.abono_empleado OWNER TO darm;

--
-- Name: id_abono_peluqueria_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_abono_peluqueria_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_abono_peluqueria_seq OWNER TO darm;

--
-- Name: abono_peluqueria; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.abono_peluqueria (
    id_abono_peluqueria integer DEFAULT nextval('public.id_abono_peluqueria_seq'::regclass) NOT NULL,
    fecha character varying(10) NOT NULL,
    monto double precision NOT NULL,
    fecha_num integer,
    login character varying(255) NOT NULL
);


ALTER TABLE public.abono_peluqueria OWNER TO darm;

--
-- Name: cliente; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.cliente (
    cliente_cedula integer NOT NULL,
    nombre character varying(30) NOT NULL,
    apellido character varying(30) NOT NULL,
    alias character varying(30),
    telf character varying(11),
    login character varying(255) NOT NULL
);


ALTER TABLE public.cliente OWNER TO darm;

--
-- Name: id_egreso_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_egreso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_egreso_seq OWNER TO darm;

--
-- Name: egreso; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.egreso (
    id_egreso integer DEFAULT nextval('public.id_egreso_seq'::regclass) NOT NULL,
    fecha character varying(10) NOT NULL,
    monto double precision NOT NULL,
    motivo text NOT NULL,
    fecha_num integer NOT NULL,
    login character varying(255) NOT NULL
);


ALTER TABLE public.egreso OWNER TO darm;

--
-- Name: empleado; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.empleado (
    empleado_cedula integer NOT NULL,
    nombre character varying(30) NOT NULL,
    apellido character varying(30) NOT NULL,
    genero character varying(1) NOT NULL,
    correo character varying,
    telf character varying(11),
    login character varying(255) NOT NULL,
    "dueño" boolean NOT NULL
);


ALTER TABLE public.empleado OWNER TO darm;

--
-- Name: id_cliente_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_cliente_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 100000
    CACHE 1;


ALTER TABLE public.id_cliente_seq OWNER TO darm;

--
-- Name: id_ingreso_deuda_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_ingreso_deuda_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_ingreso_deuda_seq OWNER TO darm;

--
-- Name: id_ingreso_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_ingreso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_ingreso_seq OWNER TO darm;

--
-- Name: id_ingreso_transferencia_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_ingreso_transferencia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_ingreso_transferencia_seq OWNER TO darm;

--
-- Name: id_motivo_ingreso_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_motivo_ingreso_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000
    CACHE 1;


ALTER TABLE public.id_motivo_ingreso_seq OWNER TO darm;

--
-- Name: id_motivo_porcentaje_ganancia_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_motivo_porcentaje_ganancia_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_motivo_porcentaje_ganancia_seq OWNER TO darm;

--
-- Name: id_porcentaje_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_porcentaje_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 10000
    CACHE 1;


ALTER TABLE public.id_porcentaje_seq OWNER TO darm;

--
-- Name: id_vale_pago_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_vale_pago_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_vale_pago_seq OWNER TO darm;

--
-- Name: id_venta_seq; Type: SEQUENCE; Schema: public; Owner: darm
--

CREATE SEQUENCE public.id_venta_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    MAXVALUE 1000000
    CACHE 1;


ALTER TABLE public.id_venta_seq OWNER TO darm;

--
-- Name: ingreso; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.ingreso (
    id_ingreso integer DEFAULT nextval('public.id_ingreso_seq'::regclass) NOT NULL,
    id_motivo_ingreso integer NOT NULL,
    cliente_cedula integer NOT NULL,
    fecha character varying(10) NOT NULL,
    fecha_num integer NOT NULL,
    efectivo boolean NOT NULL,
    transferencia boolean NOT NULL,
    debito boolean NOT NULL,
    empleado_cedula integer NOT NULL,
    login character varying(255) NOT NULL,
    monto_efectivo double precision,
    monto_debito double precision
);


ALTER TABLE public.ingreso OWNER TO darm;

--
-- Name: ingreso_deuda; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.ingreso_deuda (
    id_ingreso_deuda integer DEFAULT nextval('public.id_ingreso_deuda_seq'::regclass) NOT NULL,
    id_ingreso integer NOT NULL,
    monto double precision NOT NULL,
    pagada boolean NOT NULL
);


ALTER TABLE public.ingreso_deuda OWNER TO darm;

--
-- Name: ingreso_transferencia; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.ingreso_transferencia (
    id_ingreso_transferencia integer DEFAULT nextval('public.id_ingreso_transferencia_seq'::regclass) NOT NULL,
    fecha character varying(10) NOT NULL,
    monto double precision NOT NULL,
    referencia integer NOT NULL,
    id_ingreso integer
);


ALTER TABLE public.ingreso_transferencia OWNER TO darm;

--
-- Name: motivo_ingreso; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.motivo_ingreso (
    id_motivo_ingreso integer DEFAULT nextval('public.id_motivo_ingreso_seq'::regclass) NOT NULL,
    motivo text NOT NULL,
    login character varying(255) NOT NULL
);


ALTER TABLE public.motivo_ingreso OWNER TO darm;

--
-- Name: motivo_porcentaje_ganancia; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.motivo_porcentaje_ganancia (
    id_motivo_porcentaje_ganancia integer DEFAULT nextval('public.id_motivo_porcentaje_ganancia_seq'::regclass) NOT NULL,
    id_motivo_ingreso integer NOT NULL,
    empleado_cedula integer NOT NULL,
    fecha character varying(10) NOT NULL,
    porcentaje_empleado double precision NOT NULL,
    "porcentaje_dueño" double precision NOT NULL,
    porcentaje_peluqueria double precision NOT NULL,
    fecha_num integer NOT NULL,
    login character varying(255) NOT NULL
);


ALTER TABLE public.motivo_porcentaje_ganancia OWNER TO darm;

--
-- Name: porcentaje_ganancia; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.porcentaje_ganancia (
    id_porcentaje_ganancia integer DEFAULT nextval('public.id_porcentaje_seq'::regclass) NOT NULL,
    empleado_cedula integer NOT NULL,
    fecha character varying(10) NOT NULL,
    porcentaje_peluqueria double precision NOT NULL,
    "porcentaje_dueño" double precision NOT NULL,
    porcentaje_empleado double precision NOT NULL,
    fecha_num integer NOT NULL,
    login character varying(255) NOT NULL
);


ALTER TABLE public.porcentaje_ganancia OWNER TO darm;

--
-- Name: usuario; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.usuario (
    login character varying(255) NOT NULL,
    pass character varying(128) NOT NULL,
    nombre character varying(30) NOT NULL,
    apellido character varying(30) NOT NULL,
    administrador boolean NOT NULL,
    consulta boolean NOT NULL,
    cajero boolean
);


ALTER TABLE public.usuario OWNER TO darm;

--
-- Name: vale_pago; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.vale_pago (
    id_vale_pago integer DEFAULT nextval('public.id_vale_pago_seq'::regclass) NOT NULL,
    empleado_cedula integer NOT NULL,
    fecha character varying NOT NULL,
    monto double precision NOT NULL,
    comentario text,
    vale_pago character varying(4) NOT NULL,
    fecha_num integer,
    login character varying(255) NOT NULL
);


ALTER TABLE public.vale_pago OWNER TO darm;

--
-- Name: venta; Type: TABLE; Schema: public; Owner: darm; Tablespace: 
--

CREATE TABLE public.venta (
    id_venta integer DEFAULT nextval('public.id_venta_seq'::regclass) NOT NULL,
    fecha character varying(10) NOT NULL,
    monto double precision NOT NULL,
    motivo text NOT NULL,
    fecha_num integer,
    login character varying(255) NOT NULL
);


ALTER TABLE public.venta OWNER TO darm;

--
-- Data for Name: abono_empleado; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.abono_empleado VALUES (18, 17734140, '2018-01-03', 223423, 1514952000, 'daici');


--
-- Data for Name: abono_peluqueria; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.abono_peluqueria VALUES (6, '2018-01-24', 123, 1516766400, 'daici');


--
-- Data for Name: cliente; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.cliente VALUES (16798369, 'Daniel', 'Rodriguez', NULL, '04265821410', 'daici');


--
-- Data for Name: egreso; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.egreso VALUES (99, '2018-01-23', 120000, 'Desayuno', 1516680000, 'daici');


--
-- Data for Name: empleado; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.empleado VALUES (17734140, 'Daici', 'Coa', 'f', NULL, '04248140315', 'daici', true);
INSERT INTO public.empleado VALUES (16798369, 'Daniel', 'Rodriguez', 'm', 'darm1609@gmail.com', '04265821410', 'daici', false);


--
-- Name: id_abono_empleado_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_abono_empleado_seq', 18, true);


--
-- Name: id_abono_peluqueria_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_abono_peluqueria_seq', 6, true);


--
-- Name: id_cliente_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_cliente_seq', 1, false);


--
-- Name: id_egreso_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_egreso_seq', 99, true);


--
-- Name: id_ingreso_deuda_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_ingreso_deuda_seq', 1, false);


--
-- Name: id_ingreso_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_ingreso_seq', 2077, true);


--
-- Name: id_ingreso_transferencia_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_ingreso_transferencia_seq', 1, false);


--
-- Name: id_motivo_ingreso_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_motivo_ingreso_seq', 11, true);


--
-- Name: id_motivo_porcentaje_ganancia_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_motivo_porcentaje_ganancia_seq', 7, true);


--
-- Name: id_porcentaje_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_porcentaje_seq', 16, true);


--
-- Name: id_vale_pago_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_vale_pago_seq', 590, true);


--
-- Name: id_venta_seq; Type: SEQUENCE SET; Schema: public; Owner: darm
--

SELECT pg_catalog.setval('public.id_venta_seq', 67, true);


--
-- Data for Name: ingreso; Type: TABLE DATA; Schema: public; Owner: darm
--



--
-- Data for Name: ingreso_deuda; Type: TABLE DATA; Schema: public; Owner: darm
--



--
-- Data for Name: ingreso_transferencia; Type: TABLE DATA; Schema: public; Owner: darm
--



--
-- Data for Name: motivo_ingreso; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.motivo_ingreso VALUES (4, 'SECADO', 'daici');
INSERT INTO public.motivo_ingreso VALUES (5, 'CORTE', 'daici');
INSERT INTO public.motivo_ingreso VALUES (6, 'LAVADO', 'daici');
INSERT INTO public.motivo_ingreso VALUES (9, 'PIGMENTACIÓN DE CEJAS ', 'daici');
INSERT INTO public.motivo_ingreso VALUES (10, 'KERATINA', 'daici');
INSERT INTO public.motivo_ingreso VALUES (11, 'BUCLES', 'darm');


--
-- Data for Name: motivo_porcentaje_ganancia; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.motivo_porcentaje_ganancia VALUES (4, 5, 16798369, '2017-11-15', 50, 0, 50, 1510801845, 'darm');
INSERT INTO public.motivo_porcentaje_ganancia VALUES (5, 9, 17734140, '2018-01-21', 100, 0, 0, 1516585684, 'daici');
INSERT INTO public.motivo_porcentaje_ganancia VALUES (6, 5, 17734140, '2018-01-21', 100, 0, 0, 1516586357, 'daici');
INSERT INTO public.motivo_porcentaje_ganancia VALUES (7, 6, 17734140, '2018-01-21', 10, 0, 90, 1516586484, 'daici');


--
-- Data for Name: porcentaje_ganancia; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.porcentaje_ganancia VALUES (12, 17734140, '2018-01-21', 40, 0, 60, 1516582441, 'daici');
INSERT INTO public.porcentaje_ganancia VALUES (13, 17734140, '2018-01-21', 60, 10, 30, 1516584390, 'daici');
INSERT INTO public.porcentaje_ganancia VALUES (14, 17734140, '2018-01-21', 60, 10, 30, 1516585684, 'daici');
INSERT INTO public.porcentaje_ganancia VALUES (15, 17734140, '2018-01-21', 60, 10, 30, 1516586357, 'daici');
INSERT INTO public.porcentaje_ganancia VALUES (16, 17734140, '2018-01-21', 60, 10, 30, 1516586484, 'daici');


--
-- Data for Name: usuario; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.usuario VALUES ('daici', 'b1586709d3b7235d76cfb1c3eb564d4237bdcdc2c8487bd6e56c6c9d9eb54913b5fd0c9716c00e483dde5e88640712f1f3e796dd21de40f038a2db147423642b', 'Daici', 'Coa', true, false, false);
INSERT INTO public.usuario VALUES ('darm', 'b1586709d3b7235d76cfb1c3eb564d4237bdcdc2c8487bd6e56c6c9d9eb54913b5fd0c9716c00e483dde5e88640712f1f3e796dd21de40f038a2db147423642b', 'Daniel', 'Rodriguez', true, false, NULL);


--
-- Data for Name: vale_pago; Type: TABLE DATA; Schema: public; Owner: darm
--



--
-- Data for Name: venta; Type: TABLE DATA; Schema: public; Owner: darm
--

INSERT INTO public.venta VALUES (66, '2018-01-23', 125000, 'Tinte', 1516680000, 'daici');


--
-- Name: abono_empleado_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.abono_empleado
    ADD CONSTRAINT abono_empleado_pkey PRIMARY KEY (id_abono_empleado);


--
-- Name: abono_peluqueria_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.abono_peluqueria
    ADD CONSTRAINT abono_peluqueria_pkey PRIMARY KEY (id_abono_peluqueria);


--
-- Name: cliente_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.cliente
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (cliente_cedula);


--
-- Name: egreso_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.egreso
    ADD CONSTRAINT egreso_pkey PRIMARY KEY (id_egreso);


--
-- Name: empleado_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.empleado
    ADD CONSTRAINT empleado_pkey PRIMARY KEY (empleado_cedula);


--
-- Name: ingreso_deuda_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.ingreso_deuda
    ADD CONSTRAINT ingreso_deuda_pkey PRIMARY KEY (id_ingreso_deuda);


--
-- Name: ingreso_transferencia_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.ingreso_transferencia
    ADD CONSTRAINT ingreso_transferencia_pkey PRIMARY KEY (id_ingreso_transferencia);


--
-- Name: ingresos_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.ingreso
    ADD CONSTRAINT ingresos_pkey PRIMARY KEY (id_ingreso);


--
-- Name: motivo_ingreso_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.motivo_ingreso
    ADD CONSTRAINT motivo_ingreso_pkey PRIMARY KEY (id_motivo_ingreso);


--
-- Name: motivo_porcentaje_ganancia_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.motivo_porcentaje_ganancia
    ADD CONSTRAINT motivo_porcentaje_ganancia_pkey PRIMARY KEY (id_motivo_porcentaje_ganancia);


--
-- Name: porcentaje_ganancia_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.porcentaje_ganancia
    ADD CONSTRAINT porcentaje_ganancia_pkey PRIMARY KEY (id_porcentaje_ganancia);


--
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (login);


--
-- Name: vale_pago_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.vale_pago
    ADD CONSTRAINT vale_pago_pkey PRIMARY KEY (id_vale_pago);


--
-- Name: ventas_pkey; Type: CONSTRAINT; Schema: public; Owner: darm; Tablespace: 
--

ALTER TABLE ONLY public.venta
    ADD CONSTRAINT ventas_pkey PRIMARY KEY (id_venta);


--
-- Name: SCHEMA public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- Name: SEQUENCE id_abono_empleado_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_abono_empleado_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_abono_empleado_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_abono_empleado_seq TO darm;


--
-- Name: TABLE abono_empleado; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.abono_empleado FROM PUBLIC;
REVOKE ALL ON TABLE public.abono_empleado FROM darm;
GRANT ALL ON TABLE public.abono_empleado TO darm;


--
-- Name: SEQUENCE id_abono_peluqueria_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_abono_peluqueria_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_abono_peluqueria_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_abono_peluqueria_seq TO darm;


--
-- Name: TABLE abono_peluqueria; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.abono_peluqueria FROM PUBLIC;
REVOKE ALL ON TABLE public.abono_peluqueria FROM darm;
GRANT ALL ON TABLE public.abono_peluqueria TO darm;


--
-- Name: TABLE cliente; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.cliente FROM PUBLIC;
REVOKE ALL ON TABLE public.cliente FROM darm;
GRANT ALL ON TABLE public.cliente TO darm;


--
-- Name: SEQUENCE id_egreso_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_egreso_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_egreso_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_egreso_seq TO darm;


--
-- Name: TABLE egreso; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.egreso FROM PUBLIC;
REVOKE ALL ON TABLE public.egreso FROM darm;
GRANT ALL ON TABLE public.egreso TO darm;


--
-- Name: TABLE empleado; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.empleado FROM PUBLIC;
REVOKE ALL ON TABLE public.empleado FROM darm;
GRANT ALL ON TABLE public.empleado TO darm;


--
-- Name: SEQUENCE id_cliente_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_cliente_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_cliente_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_cliente_seq TO darm;


--
-- Name: SEQUENCE id_ingreso_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_ingreso_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_ingreso_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_ingreso_seq TO darm;


--
-- Name: SEQUENCE id_motivo_porcentaje_ganancia_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_motivo_porcentaje_ganancia_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_motivo_porcentaje_ganancia_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_motivo_porcentaje_ganancia_seq TO darm;


--
-- Name: SEQUENCE id_porcentaje_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_porcentaje_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_porcentaje_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_porcentaje_seq TO darm;


--
-- Name: SEQUENCE id_vale_pago_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_vale_pago_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_vale_pago_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_vale_pago_seq TO darm;


--
-- Name: SEQUENCE id_venta_seq; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON SEQUENCE public.id_venta_seq FROM PUBLIC;
REVOKE ALL ON SEQUENCE public.id_venta_seq FROM darm;
GRANT ALL ON SEQUENCE public.id_venta_seq TO darm;


--
-- Name: TABLE ingreso; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.ingreso FROM PUBLIC;
REVOKE ALL ON TABLE public.ingreso FROM darm;
GRANT ALL ON TABLE public.ingreso TO darm;


--
-- Name: TABLE motivo_ingreso; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.motivo_ingreso FROM PUBLIC;
REVOKE ALL ON TABLE public.motivo_ingreso FROM darm;
GRANT ALL ON TABLE public.motivo_ingreso TO darm;


--
-- Name: TABLE motivo_porcentaje_ganancia; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.motivo_porcentaje_ganancia FROM PUBLIC;
REVOKE ALL ON TABLE public.motivo_porcentaje_ganancia FROM darm;
GRANT ALL ON TABLE public.motivo_porcentaje_ganancia TO darm;


--
-- Name: TABLE porcentaje_ganancia; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.porcentaje_ganancia FROM PUBLIC;
REVOKE ALL ON TABLE public.porcentaje_ganancia FROM darm;
GRANT ALL ON TABLE public.porcentaje_ganancia TO darm;


--
-- Name: TABLE usuario; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.usuario FROM PUBLIC;
REVOKE ALL ON TABLE public.usuario FROM darm;
GRANT ALL ON TABLE public.usuario TO darm;


--
-- Name: TABLE vale_pago; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.vale_pago FROM PUBLIC;
REVOKE ALL ON TABLE public.vale_pago FROM darm;
GRANT ALL ON TABLE public.vale_pago TO darm;


--
-- Name: TABLE venta; Type: ACL; Schema: public; Owner: darm
--

REVOKE ALL ON TABLE public.venta FROM PUBLIC;
REVOKE ALL ON TABLE public.venta FROM darm;
GRANT ALL ON TABLE public.venta TO darm;


--
-- PostgreSQL database dump complete
--

