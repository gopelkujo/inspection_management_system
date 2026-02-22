# REST API Documentation

This document describes the REST API endpoints available in the Inspection System backend with Laravel 12.0.

## Base URL

The API is typically accessible under the `/api` prefix (e.g., `http://localhost:8000/api`).

---

## 1. Master Data

### `GET /master-data`

Retrieves all active master data, grouped by type.

**Response (200 OK):**

```json
{
  "inspection_type": [
    {
      "id": 1,
      "type": "inspection_type",
      "code": "NEW_ARRIVAL",
      "label": "New Arrival",
      "order": 1
    }
    // ...
  ],
  "allocations": [
    // ...
  ]
}
```

---

## 2. Inspections

### `GET /inspections`

Retrieves a paginated list of existing inspections along with their items.

**Query Parameters:**
| Parameter | Type | Description |
|-----------|--------|-------------|
| `status` | string | Filter inspections by status (e.g., `OPEN`, `FOR_REVIEW`, `COMPLETED`). |
| `page` | int | Page number for pagination. |

**Response (200 OK):**

```json
{
  "current_page": 1,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "type": "NEW_ARRIVAL",
      "status": "OPEN",
      "metadata": null,
      "created_at": "...",
      "items": [
        {
          "id": 1,
          "inspection_id": 1,
          "lot_no": "LOT-123",
          "allocation": "A1",
          "owner": "OWN-1",
          "condition": "GOOD",
          "qty_required": 10,
          "qty_available": 5
        }
      ]
    }
  ],
  "total": 1
}
```

### `POST /inspections`

Create a new inspection.

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `type` | string | Yes | Code of the inspection type (e.g., `NEW_ARRIVAL`). |
| `metadata` | array | No | Additional JSON parameters. |
| `items` | array | Yes | Array of inspection items (min 1). |

**Item Object Structure:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| `lot_no` | string | Yes | Lot number string. |
| `qty_required` | int | Yes | Minimum 1. |
| `allocation` | string | No | Allocation code. |
| `owner` | string | No | Owner code. |
| `condition` | string | No | Condition code. |
| `qty_available` | int | No | Available quantity. |

**Response (201 Created):** Returns the created inspection object with its nested items.

### `GET /inspections/{id}`

Retrieves a single inspection by its ID, complete with its associated items.

**Response (200 OK):**

```json
{
  "id": 1,
  "user_id": 1,
  "type": "NEW_ARRIVAL",
  "status": "OPEN",
  "metadata": null,
  "items": [ ... ]
}
```

### `PUT /inspections/{id}`

Update an existing inspection.
This endpoint supports two types of operations based on the payload: **Status Transition** or **Content Update**.

**1. Status Transition:** Provide just the `status` field.
Valid transitions:

- `OPEN` -> `FOR_REVIEW`
- `FOR_REVIEW` -> `COMPLETED`, `OPEN`

```json
{
  "status": "FOR_REVIEW"
}
```

**2. Content Update:** Update the inspection configuration and items. _(Only allowed when status is `OPEN`)_.
When items are provided, they completely replace the existing items for the inspection.

```json
{
  "type": "OTHER_TYPE",
  "metadata": {},
  "items": [
    {
      "lot_no": "LOT-123",
      "qty_required": 20
    }
  ]
}
```

**Response (200 OK):** Returns the updated inspection along with its items.
**Response (403 Forbidden):** Returns an error if attempting to modify content while status is not `OPEN`.
**Response (422 Unprocessable Entity):** Returns an error if transitioning to an invalid status.
