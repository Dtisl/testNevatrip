# Тестовое задание для компании **NevaTrip**

## Установка и запуск приложения

1. Скопируйте файл `.env.example` в `.env` и укажите данные для подключения к вашей базе данных.
2. Выполните команду для применения миграций: 
    ```bash
    php artisan migrate
    ```

Теперь приложение готово для использования, и можно отправлять запросы!

---

## Проблемы исходной структуры данных

В текущей структуре таблицы есть несколько проблем, которые необходимо решить для улучшения производительности и гибкости работы с заказами и билетами:

- **Смешивание разных типов билетов в одной таблице**:
    В текущей таблице хранятся все типы билетов (взрослые, детские, льготные, групповые), что приводит к дублированию данных.
  
- **Отсутствие детализированной информации о каждом билете**:
    Каждый заказ содержит общее количество билетов для каждого типа, но нет информации о каждом отдельном билете, такой как баркод и тип билета.

---

## Нормализация базы данных

Для улучшения структуры и решения вышеупомянутых проблем, мы предложили разделить данные на несколько таблиц с соответствующими связями:

### 1. **Таблица `orders`** — информация о заказе

Эта таблица будет хранить информацию о заказах, такую как ID события, дату события и общую сумму заказа.

### 2. **Таблица `tickets`** — информация о заказе

Эта таблица будет хранить информацию о билетах, такую как тип, цена и баркод.
