# Levantamiento del Proyecto Laravel con Composer

Este documento describe los pasos necesarios para levantar este proyecto Laravel en tu entorno local después de clonar el repositorio, y explica por qué ciertas carpetas y archivos no están incluidos en el repositorio.

---

## Requisitos Previos

- PHP >= 8.1
- Composer
- MySQL u otra base de datos compatible
- Node.js y npm (opcional, para assets frontend)

---

## Pasos para levantar el proyecto

1. **Clona el repositorio**

```bash
git clone https://github.com/daniel2005-ctrl/MyR.git
cd MyR
```

2. **Instala las dependencias del backend (Laravel)**

```bash
composer install
```

3. **Copia el archivo de entorno**

```bash
cp .env.example .env
```

4. **Configura tu archivo `.env`**

Edita el archivo `.env` con tu configuración local de base de datos, correo, y otros servicios.

5. **Genera la clave de la aplicación**

```bash
php artisan key:generate
```

6. **Ejecuta las migraciones (si aplica)**

```bash
php artisan migrate
```

7. **Instala las dependencias del frontend (opcional)**

```bash
npm install && npm run dev
```

8. **Levanta el servidor local**

```bash
php artisan serve
```

---

## ¿Por qué no se sube la carpeta `vendor/` al repositorio?

La carpeta `vendor/` contiene todas las dependencias de PHP que Laravel necesita para funcionar. Esta carpeta **no debe subirse al repositorio por las siguientes razones:**

- **Peso:** Puede pesar cientos de MB innecesariamente.
- **Portabilidad:** Las dependencias pueden variar por sistema operativo o entorno.
- **Estándar profesional:** Laravel (y todo proyecto PHP moderno) usa **Composer** para gestionar dependencias. Lo correcto es subir solo `composer.json` y `composer.lock`.

> Quien clone el proyecto debe ejecutar `composer install`, que automáticamente descargará y reconstruirá la carpeta `vendor/` según el archivo `composer.lock`.

---

## ¿Por qué no se sube el archivo `.env`?

El archivo `.env` contiene información **sensible y específica del entorno local**, como credenciales de bases de datos, claves de API y configuración de correo.

Por razones de seguridad:

- **Nunca debe compartirse públicamente.**
- Cada desarrollador debe tener su propio `.env` personalizado.

Por eso se incluye un archivo `.env.example` como plantilla, para que cada integrante del equipo pueda copiarlo y configurarlo localmente.

---

## ¿Qué más no se sube y por qué?

- `node_modules/`: Al igual que `vendor/`, es generado por `npm install`.
- Cachés (`bootstrap/cache/`, archivos `.log`, etc.): No aportan al código fuente y se regeneran automáticamente.
- Archivos temporales: Laravel genera muchos archivos en `storage/` que son específicos del entorno de ejecución.

---

## Buenas prácticas

- No subir nunca archivos generados automáticamente.
- Mantener actualizado el `.env.example` con las claves necesarias (sin valores sensibles).
- Documentar siempre los pasos para iniciar el proyecto en este archivo o en el `README.md`.

---

**Este documento está diseñado para que cualquier desarrollador pueda levantar el proyecto Laravel de forma segura, limpia y rápida.**