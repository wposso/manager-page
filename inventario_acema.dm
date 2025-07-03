
Table usuario {
  id int [pk, increment]
  nombre varchar(100)
  correo varchar(100) [unique]
  contraseña varchar(255)
  rol enum('ADMIN', 'OPERADOR', 'SUPERVISOR')
  activo boolean
  creado_en datetime
  actualizado_en datetime
}

Table bodega {
  id int [pk, increment]
  nombre varchar(100)
  ubicacion text
  descripcion text
  estado boolean
  creado_en datetime
  actualizado_en datetime
}

Table categoria {
  id int [pk, increment]
  nombre varchar(100)
  descripcion text
  estado boolean
  creado_en datetime
  actualizado_en datetime
}

Table subcategoria {
  id int [pk, increment]
  nombre varchar(100)
  descripcion text
  categoria_id int [ref: > categoria.id]
  estado boolean
  creado_en datetime
  actualizado_en datetime
}

Table proveedor {
  id int [pk, increment]
  nombre varchar(100)
  nit varchar(20) [unique]
  contacto varchar(100)
  telefono varchar(20)
  correo varchar(100)
  direccion text
  creado_en datetime
  actualizado_en datetime
}

Table inventario {
  id int [pk, increment]
  codigo varchar(50) [unique]
  nombre varchar(100)
  cantidad int
  categoria_id int [ref: > categoria.id]
  subcategoria_id int [ref: > subcategoria.id]
  proveedor_id int [ref: > proveedor.id]
  bodega_id int [ref: > bodega.id]
  estado boolean
  creado_en datetime
  actualizado_en datetime
}

Table novedad {
  id int [pk, increment]
  titulo varchar(150)
  descripcion text
  tipo enum('DAÑO', 'FALTA', 'SOBRANTE', 'REUBICACION', 'OTRO')
  bodega_id int [ref: > bodega.id]
  fecha datetime
}

Table movimiento {
  id int [pk, increment]
  tipo enum('ENTRADA', 'SALIDA', 'TRASLADO', 'AJUSTE')
  producto_id int [ref: > inventario.id]
  cantidad int
  bodega_origen_id int [ref: > bodega.id]
  bodega_destino_id int [ref: > bodega.id]
  motivo text
  usuario_responsable varchar(100)
  fecha datetime
}
