# PassTransfer Helm Chart

Helm chart для развертывания приложения PassTransfer в Kubernetes.

## Установка

### Добавление репозитория

```bash
helm repo add pass-transfer https://vasyakrg.github.io/pass-transfer
helm repo update
```

### Установка чарта

```bash
# Установка с именем release
helm install my-pass-transfer ./helm

# Установка в определенном namespace
helm install my-pass-transfer ./helm --namespace pass-transfer --create-namespace

# Установка с кастомными значениями
helm install my-pass-transfer ./helm -f values-custom.yaml
```

## Конфигурация

### Основные параметры

| Параметр           | Описание                     | По умолчанию                     |
|--------------------|------------------------------|----------------------------------|
| `replicaCount`     | Количество реплик приложения | `1`                              |
| `image.repository` | Репозиторий Docker образа    | `ghcr.io/vasyakrg/pass-transfer` |
| `image.tag`        | Тег Docker образа            | `latest`                         |
| `image.pullPolicy` | Политика загрузки образа     | `IfNotPresent`                   |
| `service.type`     | Тип Kubernetes Service       | `ClusterIP`                      |
| `ingress.enabled`  | Включить Ingress             | `false`                          |

### База данных

| Параметр                                     | Описание                  | По умолчанию    |
|----------------------------------------------|---------------------------|-----------------|
| `database.enabled`                           | Включить MySQL            | `true`          |
| `database.mysql.auth.database`               | Имя базы данных           | `pass_transfer` |
| `database.mysql.auth.username`               | Пользователь БД           | `passuser`      |
| `database.mysql.auth.password`               | Пароль БД                 | `pass123`       |
| `database.mysql.primary.persistence.enabled` | Включить PersistentVolume | `true`          |
| `database.mysql.primary.persistence.size`    | Размер хранилища          | `8Gi`           |

### Приложение

| Параметр                   | Описание          | По умолчанию                  |
|----------------------------|-------------------|-------------------------------|
| `config.debug`             | Режим отладки     | `false`                       |
| `config.companyWhitelabel` | Название компании | `PassTransfer`                |
| `config.siteUrl`           | URL сайта         | `https://pass-transfer.local` |

### Ресурсы

| Параметр                    | Описание      | По умолчанию |
|-----------------------------|---------------|--------------|
| `resources.limits.cpu`      | Лимит CPU     | `500m`       |
| `resources.limits.memory`   | Лимит памяти  | `512Mi`      |
| `resources.requests.cpu`    | Запрос CPU    | `250m`       |
| `resources.requests.memory` | Запрос памяти | `256Mi`      |

### Автомасштабирование

| Параметр                                     | Описание             | По умолчанию |
|----------------------------------------------|----------------------|--------------|
| `autoscaling.enabled`                        | Включить HPA         | `false`      |
| `autoscaling.minReplicas`                    | Минимум реплик       | `1`          |
| `autoscaling.maxReplicas`                    | Максимум реплик      | `100`        |
| `autoscaling.targetCPUUtilizationPercentage` | Целевая загрузка CPU | `80`         |

## Примеры конфигурации

### Продакшн конфигурация

```yaml
# values-prod.yaml
replicaCount: 3

image:
  repository: ghcr.io/vasyakrg/pass-transfer
  tag: "v1.0.0"

ingress:
  enabled: true
  className: "nginx"
  annotations:
    cert-manager.io/cluster-issuer: "letsencrypt-prod"
  hosts:
    - host: pass-transfer.yourdomain.com
      paths:
        - path: /
          pathType: Prefix
  tls:
    - secretName: pass-transfer-tls
      hosts:
        - pass-transfer.yourdomain.com

database:
  mysql:
    auth:
      password: "your-secure-password"
    primary:
      persistence:
        size: 20Gi

autoscaling:
  enabled: true
  minReplicas: 2
  maxReplicas: 10
  targetCPUUtilizationPercentage: 70

resources:
  limits:
    cpu: 1000m
    memory: 1Gi
  requests:
    cpu: 500m
    memory: 512Mi
```

### Локальная разработка

```yaml
# values-dev.yaml
replicaCount: 1

image:
  repository: ghcr.io/vasyakrg/pass-transfer
  tag: "latest"

config:
  debug: true

database:
  mysql:
    primary:
      persistence:
        enabled: false

resources:
  limits:
    cpu: 250m
    memory: 256Mi
  requests:
    cpu: 100m
    memory: 128Mi
```

## Обновление

```bash
# Обновление release
helm upgrade my-pass-transfer ./helm

# Обновление с новыми значениями
helm upgrade my-pass-transfer ./helm -f values-new.yaml

# Обновление с откатом
helm upgrade my-pass-transfer ./helm --atomic
```

## Удаление

```bash
# Удаление release
helm uninstall my-pass-transfer

# Удаление с очисткой данных
helm uninstall my-pass-transfer
kubectl delete pvc -l app.kubernetes.io/instance=my-pass-transfer
```

## Troubleshooting

### Проверка статуса

```bash
# Статус release
helm status my-pass-transfer

# Логи приложения
kubectl logs -l app.kubernetes.io/name=pass-transfer

# Логи базы данных
kubectl logs -l app.kubernetes.io/component=database

# Описание подов
kubectl describe pods -l app.kubernetes.io/name=pass-transfer
```

### Подключение к базе данных

```bash
# Порт-форвард MySQL
kubectl port-forward svc/my-pass-transfer-mysql 3306:3306

# Подключение к MySQL
mysql -h localhost -P 3306 -u passuser -p pass_transfer
```

### Проверка Ingress

```bash
# Проверка Ingress
kubectl get ingress
kubectl describe ingress my-pass-transfer

# Проверка DNS
nslookup pass-transfer.yourdomain.com
```

## Требования

- Kubernetes 1.19+
- Helm 3.0+
- Ingress Controller (если используется Ingress)
- Storage Class (если используется PersistentVolume)
