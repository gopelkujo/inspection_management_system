# Item Inspection Management System

An intuitive Single Page Application (SPA) designed to streamline the management of item inspections within a warehouse or inventory environment. Built with Laravel 12.0 and Vue 3.

---

## 📖 Project Description

The **Item Inspection Management System** serves as a centralized hub for managing quality control, arrival checks, and inventory assessments. In many warehouse operations, inspecting items requires meticulous tracking of different data points, such as lot numbers, allocations, conditions, and quantities. This system replaces manual or fragmented tracking methods by offering a digital, standardized, and responsive interface where inspectors and managers can seamlessly record and review inspection data.

## 🚀 How to Use the App & Full Flow

### 1. Master Data Configuration

Before creating inspections, the application relies on **Master Data** to populate dropdowns and standardized fields (e.g., categories, item conditions, inspection statuses). This ensures data consistency across the entire application.

### 2. Creating an Inspection

Users can create a new inspection by specifying the type of inspection (e.g., "New Arrival", "Routine Check").

- An inspection record acts as a parent container.
- Within this inspection, users can add multiple **Inspection Items**.

### 3. Adding Inspection Items

For each item being inspected, the user inputs specific details:

- **Lot No**: The batch or lot identifier.
- **Allocation**: Where the item is allocated or destined.
- **Owner**: The entity or department that owns the item.
- **Condition**: The physical state of the item (e.g., Good, Damaged).
- **Quantities**: Tracking how many units are available versus how many are required or expected.

### 4. Status Progression (The Flow)

The lifecycle of an inspection follows a strict workflow:

1. **Open**: A new inspection is created. Items are being actively added and inspected.
2. **For Review**: The inspection is submitted for a manager or supervisor to review the recorded findings.
3. **Completed**: The inspection is finalized and locked.

**Why this flow?**
This 3-step workflow ensures accountability and accuracy. By separating the "Open" (data entry) phase from the "For Review" (validation) phase, it prevents erroneous data from being finalized immediately. It guarantees that a second pair of eyes can verify the inspection results before they are marked as "Completed" and affect downstream inventory processes.

## 📊 Existing Attribute Data (Entities)

The application structure is built around three core data entities:

### 1. Inspections

The parent record representing a single inspection event.

- **`type`**: The category of the inspection (references master data).
- **`status`**: The current stage in the workflow (`open` -> `for review` -> `completed`).
- **`metadata`**: Flexible JSON storage for any additional, specific data needed for particular inspection types.

### 2. Inspection Items

The individual items or lots being examined within an inspection.

- **`lot_no`**: Batch tracking number.
- **`allocation`**: Physical or logical destination of the item.
- **`owner`**: The registered owner of the lot.
- **`condition`**: Physical status (e.g., intact, broken).
- **`qty_available`**: The actual physical quantity counted.
- **`qty_required`**: The expected or requested quantity.

### 3. Master Data

A centralized dictionary to keep the application's data clean and standardized.

- **`type`**: The category of the data (e.g., 'category', 'status', 'condition').
- **`code`**: A unique system identifier (e.g., 'NEW_ARRIVAL').
- **`label`**: The human-readable name displayed in the UI.
- **`value`**: Optional extra parameters.
- **`is_active`**: Toggles whether this option is currently selectable in the app.

---

## 🛠 Tech Stack

- **Backend**: Laravel 12.0, Sanctum, SQLite (for dev/testing), Pest/PHPUnit
- **Frontend**: Vue 3, Vite, Vuex, Vue Router, TailwindCSS
- **Testing**: Vitest (Frontend), PHPUnit (Backend)

## ✨ Features

- **Inspection Management**: Create, view, and manage inspections.
- **Workflow**: Track status (Open -> For Review -> Completed).
- **Master Data**: Centralized management of types, statuses, conditions, etc.
- **Responsive UI**: Built with TailwindCSS for a modern look.

## ⚙️ Setup Instructions

### Backend

1. Navigate to `backend` directory:
   ```bash
   cd backend
   ```
2. Install dependencies:
   ```bash
   composer install
   ```
3. Setup environment:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Run migrations and seeders:
   ```bash
   php artisan migrate --seed
   ```
   _(Note: The application uses SQLite by default. If prompted to create the SQLite database file during this step, type "yes".)_
5. Start server:
   ```bash
   php artisan serve
   ```

### Frontend

1. Navigate to `frontend` directory:
   ```bash
   cd frontend
   ```
2. Install dependencies:
   ```bash
   npm install
   ```
3. Start development server:
   ```bash
   npm run dev
   ```

## 🧪 Testing

- **Backend**: `php artisan test`
- **Frontend**: `npm run test` (or `npx vitest run`)

## 📚 Documentation

- REST API documentation is available in [API_DOCS.md](API_DOCS.md).
- API Routes are defined in `routes/api.php`.
- Frontend stores are in `src/stores`.
- Master data is seeded via `MasterDataSeeder`.
