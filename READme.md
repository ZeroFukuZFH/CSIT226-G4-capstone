# 👥 Project: Members

---

## 🔑 Important Keywords

* **`include`**: The "bridge" of PHP. Works like `#include` in C/C++, allowing you to pull logic from one PHP file into another to keep code modular and dry.

---

## Tips on development
* when designing the UI using another text editor / IDE like VS code, I recommend first creating a .html file then transforming it into .php later when finished so you can make full use of both intellisense features (assuming that you aren't just vibe-coding ofcourse but regardless semi-helpful tip)

## 📂 Folder Structure
>
> [!NOTE]  
> *This structure follows a modular design pattern to keep concerns separated.*

### 🧠 `app/`

* **Purpose:** The Business Logic layer.
* **Role:** Processes data and handles the "how" of the application.

### 🗄️ `data/`

* **Purpose:** Data Access Layer (DAL).
* **Role:** Dedicated to database interactions—fetching, posting, and raw SQL logic.

### 🎨 `ui/`

* **Purpose:** Presentation Layer.
* **Role:** Front-end design, HTML templates, CSS styling, and user-facing views.

### 🛠️ `utils/`

* **Purpose:** Quality of Life (QoL) Helpers.
* **Role:** Generic utility functions (e.g., `countOdd(entity : T)`) that aren't tied to a specific feature.

---

## ⚠️ Disclaimer

**SUBJECT TO CHANGE:** This architecture is inspired by modern full-stack paradigms (React/MVC). It is adapted for XAMPP to avoid a "flat" file mess and ensure the project remains scalable as complexity grows.
