## **⚙️ Instalasi**

### 1. Clone Repository

```bash
git clone https://github.com/akira4n/BE_StudiKasus1.git
cd BE_StudiKasus1
```

### 2. Setup Database

- Buat database MySQL baru bernama startup_ticketing.
- Import file [`db.sql`](db.sql) ke dalam database tersebut.

### 3. Konfigurasi Database

- Silahkan config file [`config/database.php`](config/database.php).

### 4. Run Server

```bash
php -S localhost:8000 -t public
```

## **📋 Requirements**

- PHP 8.x
- MySQL / MariaDB

## **📑 Dokumentasi API**

### **1. Tambah data user**

```http
POST /api/users
```

**Request Body:**

```json
{
  "name": "Syawal",
  "email": "syawal@example.com"
}
```

**Response:**

```json
{
  "message": "User berhasil didaftarkan"
}
```

### **2. Ambil semua data user**

```http
GET /api/users
```

**Response:**

```json
[
  {
    "id": 1,
    "name": "Syawal",
    "email": "syawal@example.com",
    "created_at": "2025-12-23 19:08:08"
  }
]
```

### **3. Tambah data event**

```http
POST /api/events
```

**Request Body:**

```json
{
  "title": "Konser Musik",
  "capacity": 50
}
```

**Response:**

```json
{
  "message": "Event berhasil dibuat"
}
```

### **4. Ambil semua data event**

```http
GET /api/events
```

**Response:**

```json
[
  {
    "id": 1,
    "title": "Konser Musik",
    "total_capacity": 50,
    "remaining_stock": 50,
    "created_at": "2025-12-23 19:10:15"
  }
]
```

### **5. Tambah data booking**

```http
POST /api/bookings
```

**Request Body:**

```json
{
  "user_id": 1,
  "event_id": 1
}
```

**Response:**

```json
{
  "message": "Tiket berhasil di pesan"
}
```

### **6. Ambil semua data booking**

```http
GET /api/bookings
```

**Response:**

```json
[
  {
    "id": 1,
    "user_name": "Syawal",
    "event_title": "Konser Musik",
    "status": "paid",
    "created_at": "2025-12-23 19:11:40"
  }
]
```

### **7. Ganti status booking/tiket**

```http
PATCH /api/bookings
```

**Request Body:**

```json
{
  "id": 1,
  "status": "cancelled"
}
```

**Response:**

```json
{
  "message": "Status booking berhasil diperbarui!"
}
```
