# Versionado del Proyecto

Este proyecto sigue el Versionado Semántico (SemVer v0.0.0).

## Formato de Versiones

- **MAJOR**: Cambios incompatibles con versiones anteriores.
- **MINOR**: Nuevas funcionalidades que son compatibles con versiones anteriores.
- **PATCH**: Correcciones de errores que son compatibles con versiones anteriores.

# Git git flow 

1. Rama principal (master): Rama principal donde siempre se encuentra el código en producción.

2. Rama de desarrollo (develop): Se integran todas las características que se están desarrollando. Rama que siempre debe estar estable y lista para ser fusionada con la rama master.

3. Ramas de características (feature): Se crean a partir de develop para trabajar en nuevas funcionalidades. Una vez completada la funcionalidad, se fusionan de nuevo en develop.

4. Ramas de lanzamiento (release): Se crean a partir de develop cuando se está listo para preparar una nueva versión. Aquí se realizan pruebas y correcciones de errores. Una vez que todo está listo, se fusionan tanto en master como en develop.

5. Ramas de corrección de errores (hotfix): Se crean a partir de master para corregir errores críticos en producción. Después de la corrección, se fusionan tanto en master como en develop.

## Git flow ejemplo
 
1. git flow feature start app-name
2. git add .
3. git commit -m "STYLE(web): Change app name"
4. git flow feature finish app-name
5. git checkout develop 
6. git flow release start v0.1.0 
5. git add .
6. git commit -m "STYLE(web) Change app name"
7. git flow release finish v0.1.0
8. git push origin develop 
9. git push origin master --tags


# Buenas practicas para el historico del proyecto git

https://github.com/midudev/midu.dev/blob/master/content/posts/buenas-practicas-escribir-commits-git.md

1. Verbos imperativos
Add
Change
Fix
Remove

2. Prefijos
feat: Una nueva característica para el usuario.
fix: Arregla un bug que afecta al usuario.
perf: Cambios que mejoran el rendimiento del sitio.
build: Cambios en el sistema de build, tareas de despliegue o instalación.
ci: Cambios en la integración continua.
docs: Cambios en la documentación.
refactor: Refactorización del código como cambios de nombre de variables o funciones.
style: Cambios de formato, tabulaciones, espacios o puntos y coma, etc; no afectan al usuario.
test: Añade tests o refactoriza uno existente.

## Estabilidad 

1. dev
2. alpha
3. beta
4. RC
5. stable
