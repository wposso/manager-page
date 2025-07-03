
# 📦 Sistema de Inventario - ACEMA

Este proyecto es un sistema de gestión de inventario diseñado para controlar productos, bodegas, movimientos, proveedores y usuarios dentro de la organización ACEMA.

---

## 📘 Estructura de la Base de Datos

### 🧑‍💼 `usuario`
Almacena la información de los usuarios que acceden al sistema.

- `id` (PK): Identificador único.
- `nombre`: Nombre completo del usuario.
- `correo`: Correo electrónico único.
- `contraseña`: Contraseña encriptada.
- `rol`: ADMIN, OPERADOR o SUPERVISOR.
- `activo`: Si el usuario está habilitado.
- `creado_en` / `actualizado_en`: Timestamps de auditoría.

---

### 🏢 `bodega`
Ubicaciones físicas donde se almacena el inventario.

- `id` (PK)
- `nombre`
- `ubicacion`
- `descripcion`
- `estado`: Activa o inactiva.
- `creado_en` / `actualizado_en`

---

### 📂 `categoria`
Clasificación general de los productos.

- `id` (PK)
- `nombre`
- `descripcion`
- `estado`
- `creado_en` / `actualizado_en`

---

### 📁 `subcategoria`
Subclasificación relacionada a una categoría.

- `id` (PK)
- `nombre`
- `descripcion`
- `categoria_id` (FK → categoria.id)
- `estado`
- `creado_en` / `actualizado_en`

---

### 🧾 `proveedor`
Información de los proveedores que suministran productos.

- `id` (PK)
- `nombre`
- `nit`: Número de identificación tributaria (único)
- `contacto`
- `telefono`
- `correo`
- `direccion`
- `creado_en` / `actualizado_en`

---

### 📦 `inventario`
Productos registrados en el sistema.

- `id` (PK)
- `codigo`: Código único del producto.
- `nombre`
- `cantidad`: Stock actual.
- `categoria_id` (FK)
- `subcategoria_id` (FK)
- `proveedor_id` (FK)
- `bodega_id` (FK)
- `estado`
- `creado_en` / `actualizado_en`

---

### ⚠️ `novedad`
Registra observaciones o incidencias relacionadas con inventario.

- `id` (PK)
- `titulo`
- `descripcion`
- `tipo`: DAÑO, FALTA, SOBRANTE, REUBICACION, OTRO
- `bodega_id` (FK)
- `fecha`

---

### 🔄 `movimiento`
Registro de entradas, salidas, traslados o ajustes de inventario.

- `id` (PK)
- `tipo`: ENTRADA, SALIDA, TRASLADO, AJUSTE
- `producto_id` (FK → inventario.id)
- `cantidad`
- `bodega_origen_id` (FK)
- `bodega_destino_id` (FK)
- `motivo`: Justificación del movimiento.
- `usuario_responsable`: Nombre del usuario que ejecuta el movimiento.
- `fecha`

---

## 🔗 Relaciones Clave
- Un `inventario` pertenece a una `bodega`, `categoria`, `subcategoria`, y `proveedor`.
- Un `movimiento` puede implicar 1 o 2 bodegas (origen/destino).
- Una `subcategoria` depende de una `categoria`.
- Las `novedades` están asociadas a una `bodega`.

---

## 🧠 Recomendaciones
- Asegura la integridad con claves foráneas.
- Usa roles (`usuario.rol`) para restringir acceso a funciones.
- Hashea las contraseñas antes de almacenarlas.

---

## 🛠️ Autor
Desarrollado para @dobleu | Proyecto de Control de Inventario 2025
