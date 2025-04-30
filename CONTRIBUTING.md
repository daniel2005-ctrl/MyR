
# 🛠️ Contribución al Proyecto MYR

## 🚀 Uso de ramas

### Ramas disponibles
- `main` → Rama principal, solo el líder puede hacer merges aquí.
- `mejora-index` → Cambios relacionados con el diseño o contenido del index.
- `mejora-proyectos` → Cambios en los proyectos mostrados.
- `mejora-otros` → Ajustes generales (por ejemplo, cotizaciones, vistas menores, etc).
- `mejora-conexiones` → Conexiones backend, lógica de formularios, autenticación, etc.
- `admin-index`
- `admin-proyectos`
- `admin-otros`
- `admin-conexiones`

## 🧑‍💻 ¿Cómo contribuir?

### 1. Clona el repositorio (solo la primera vez)
```bash
git clone https://github.com/usuario/proyecto.git
cd proyecto
```

### 2. Cambia a la rama en la que vas a trabajar
```bash
git checkout nombre-de-la-rama
```
Ejemplo:
```bash
git checkout mejora-otros
```

### 3. Realiza tus cambios y guárdalos
```bash
git add .
git commit -m "Descripción de lo que hiciste"
```

### 4. Sube tus cambios a GitHub
```bash
git push origin nombre-de-la-rama
```

## ✅ ¿Cómo se integran los cambios al proyecto?

Solo el líder del proyecto:
```bash
git checkout main
git pull origin main
git merge nombre-de-la-rama
git push origin main
```
