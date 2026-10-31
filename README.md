# Project 1 - README

This is a completed Project 1 for ASE230.
It implements all 10 required endpoints (2 of them Bearer-token protected).

## Structure

```
project1/
├── code/
│   ├── schema.sql
│   ├── db.php
│   ├── auth.php
│   ├── index.php
│   ├── test\_curl.sh
│   └── test\_client.html
├── presentation/
│   ├── api\_tutorial.md / .pdf
│   ├── nginx\_deployment.md / .pdf
│   └── nginx\_screenshot.png
├── plan/
│   └── hw2\_plan.md
│   └── hw2\_plan(updated).md
├── README.md
└── project1\_rubric.md
```

## Endpoints

|#|Method|Endpoint|Auth|
|-|-|-|-|
|1|GET|/students|public|
|2|GET|/courses|public|
|3|POST|/students|public|
|4|POST|/courses|public|
|5|GET|/students/{id}|public|
|6|GET|/courses/{id}|public|
|7|PUT|/students|public|
|8|PUT|/courses|public|
|9|DELETE|/students/{id}|Bearer token|
|10|DELETE|/courses/{id}|Bearer token|



## What you need

* PHP with `pdo\_mysql`
* MySQL server
* cURL

Note: Ensure MySQL is running before starting.



### Step 1: Create the database

From the **`project1/`** directory, create the project database and import the initial schema:



```bash
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS project1;"
mysql -u root -p project1 < code/schema.sql
```

> \[!NOTE]
> `code/schema.sql` creates the tables and inserts samples (John, Jane, and two courses). It is intended for \*\*initial setup\*\*; running it repeatedly without dropping the database will result in duplicate records.
> If you ever want to completely wipe and re-initialize a clean database, run:
> ```bash
> mysql -u root -p -e "DROP DATABASE IF EXISTS project1; CREATE DATABASE project1;"
> mysql -u root -p project1 < code/schema.sql
> ```



### Step 2: Start the PHP Server

`db.php` connects to MySQL using the environment variables `DB\_USER` (defaults to `root`) and `DB\_PASSWORD` (defaults to ase230).

From the **`project1/`** directory:

```bash
cd code

# If your MySQL root account requires a password (recommended):
DB\_PASSWORD='ase230' php -S localhost:8080

```

The API is now running at `http://localhost:8080/`. Keep this terminal open while running tests.



### Step 3: Run the Tests

In a **second terminal window**, from the **`project1/`** directory:

* **Automated cURL Tests**:

```bash
  bash code/test\_curl.sh
  ```

* **Browser Test Client**:
Open the following address in your browser:

```text
  http://localhost:8080/test\_client.html
  ```

> \[!WARNING]
  > \*\*Never\*\* open `test\_client.html` directly from your operating system file manager (`file:///...`). Browser security blocks API requests from local file URLs. Always access it through `http://localhost:8080/test\_client.html` while the server is running.



### Step 4: Stop the Server

When finished testing, return to the first terminal where the PHP server is running and press **`Ctrl+C`**.



## You have successfully complete the project!

\---

