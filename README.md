# Cargar tipo usuario
```sql
INSERT INTO public.tipo_usuario (tipo, created_at, updated_at)
VALUES
    ('admin', NOW(), NOW()),
    ('empleado', NOW(), NOW());
```
---

# Cargar formatos de placas
```sql
INSERT INTO placa_formatos (pais, code, regex, ejemplo, bandera_icon, created_at, updated_at)
VALUES
-- Bolivia
('Bolivia', 'BO', '^[0-9]{4}[A-Z]{3}$', '1234ABC', NULL, NOW(), NOW()),
-- Argentina
('Argentina', 'AR', '^[A-Z]{2}[0-9]{3}[A-Z]{2}$', 'AB123CD', NULL, NOW(), NOW()),
-- Chile
('Chile', 'CL', '^[A-Z]{4}[0-9]{2}$', 'ABCD12', NULL, NOW(), NOW()),
-- Perú
('Peru', 'PE', '^[A-Z0-9]{1}[0-9]{3}[A-Z]{2}$', 'A123BC', NULL, NOW(), NOW()),
-- Brasil
('Brasil', 'BR', '^[A-Z]{3}[0-9][A-Z][0-9]{2}$', 'ABC1D23', NULL, NOW(), NOW()),
-- México
('Mexico', 'MX', '^[A-Z]{3}-[0-9]{3}$', 'ABC-123', NULL, NOW(), NOW()),
-- USA (genérico)
('Estados Unidos', 'US', '^[A-Z0-9]{1,7}$', '7ABC123', NULL, NOW(), NOW()),
-- España
('España', 'ES', '^[0-9]{4}[A-Z]{3}$', '1234BCD', NULL, NOW(), NOW()),
-- Colombia
('Colombia', 'CO', '^[A-Z]{3}[0-9]{3}$', 'ABC123', NULL, NOW(), NOW()),
-- Paraguay
('Paraguay', 'PY', '^[A-Z]{3}[0-9]{3}$', 'ABC123', NULL, NOW(), NOW());

```
---

# 🚗 Inserción de Tipos de Espacio y Espacios del Estacionamiento (PostgreSQL)

El siguiente script crea los **tipos de espacio** y genera una distribución **lógica y optimizada** para dos pisos del estacionamiento.

* **Piso 1:**

  * 2 Discapacitados
  * 4 Motos
  * 3 Camiones
  * 10 Autos normales

* **Piso 2:**

  * 2 Motos
  * 12 Autos normales

Los códigos siguen el formato:

```
P{piso}-{TIPO}{número}
Ejemplo: P1-A03  → Piso 1, Auto normal #03
```

---

## 🟦 Script SQL Completo (PostgreSQL)

```sql
-- =============================================
-- INSERT DE TIPOS DE ESPACIO
-- =============================================
INSERT INTO tipo_espacio (nombre, descripcion, tarifa_hora) VALUES
('Auto normal', 'Espacio estándar para autos', 5.00),
('Moto', 'Espacio pequeño para motocicletas', 2.00),
('Discapacitado', 'Espacio reservado para personas con discapacidad', 0.00),
('Camiones', 'Espacio amplio para camiones o vehículos pesados', 8.00);

-- =============================================
-- ESPACIOS PARA PISO 1
-- =============================================

-- Discapacitados (2)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(1, 3, 'P1-DIS01', 'libre'),
(1, 3, 'P1-DIS02', 'libre');

-- Motos (4)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(1, 2, 'P1-MOT01', 'libre'),
(1, 2, 'P1-MOT02', 'libre'),
(1, 2, 'P1-MOT03', 'libre'),
(1, 2, 'P1-MOT04', 'libre');

-- Camiones (3)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(1, 4, 'P1-CAM01', 'libre'),
(1, 4, 'P1-CAM02', 'libre'),
(1, 4, 'P1-CAM03', 'libre');

-- Autos normales (10)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(1, 1, 'P1-A01', 'libre'),
(1, 1, 'P1-A02', 'libre'),
(1, 1, 'P1-A03', 'libre'),
(1, 1, 'P1-A04', 'libre'),
(1, 1, 'P1-A05', 'libre'),
(1, 1, 'P1-A06', 'libre'),
(1, 1, 'P1-A07', 'libre'),
(1, 1, 'P1-A08', 'libre'),
(1, 1, 'P1-A09', 'libre'),
(1, 1, 'P1-A10', 'libre');

-- =============================================
-- ESPACIOS PARA PISO 2
-- =============================================

-- Motos (2)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(2, 2, 'P2-MOT01', 'libre'),
(2, 2, 'P2-MOT02', 'libre');

-- Autos normales (12)
INSERT INTO espacio (piso_id, tipo_espacio_id, codigo, estado) VALUES
(2, 1, 'P2-A01', 'libre'),
(2, 1, 'P2-A02', 'libre'),
(2, 1, 'P2-A03', 'libre'),
(2, 1, 'P2-A04', 'libre'),
(2, 1, 'P2-A05', 'libre'),
(2, 1, 'P2-A06', 'libre'),
(2, 1, 'P2-A07', 'libre'),
(2, 1, 'P2-A08', 'libre'),
(2, 1, 'P2-A09', 'libre'),
(2, 1, 'P2-A10', 'libre'),
(2, 1, 'P2-A11', 'libre'),
(2, 1, 'P2-A12', 'libre');
```
