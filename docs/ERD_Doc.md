# ERD Documentation

## User

- id (PK)
- first_name
- last_name
- phone
- email
- password
- created_at
- updated_at

---

## Favourites

- id (PK)
- user_id (FK)
- product_id (FK)

---

## Product

- id (PK)
- category_id (FK)
- name
- manufacturer
- description
- price
- rating
- created_at
- updated_at

---

## ProductImages

- id (PK)
- product_id (FK)
- image

---

## ProductCategories

- id (PK)
- name

---

## ProductPenalties

- id (PK)
- name
- penalty

---

## Carts

- id (PK)
- user_id (FK)
- product_id (FK)
- quantity

---

## Orders

- id (PK)
- user_id (FK)
- created_at
- updated_at

---

## Order_Product

- id (PK)
- order_id (FK)
- product_id (FK)
- quantity

---

## Reviews

- id (PK)
- user_id (FK)
- product_id (FK)
- rating
- content
- created_at
- updated_at
