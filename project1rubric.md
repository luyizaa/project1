# Project 1 Grading and Submission

**Total: 100%**

> \\\*\\\*Submission format:\\\*\\\* Submit \\\*\\\*one ZIP file\\\*\\\* containing everything — `code/`, `presentation/`, `plan/`, this completed rubric file (`project1\\\_rubric.md`), and your `README.md`. \\\*\\\*If the completed rubric or README.md is missing, the submission cannot be graded.\\\*\\\*

\---

## Grading Policy and Rules

* Students must complete this checklist and self-assessment before submission.
* The instructor may re-evaluate any section and add or deduct percentage points based on actual quality and completeness.
* All Marp slide files must also be exported to PDF before submission.
* Check Canvas or ask the instructor if any submission detail is unclear.
* Project 1 is individual work. You must write and understand your own code, tests, documentation, and presentation.
* AI may explain concepts, debug code you wrote, or explain documentation, but you may not submit AI-generated work that you do not understand.
* If AI-generated work is discovered in your submission (code, tests, slides, or documentation you cannot explain or did not write yourself), this project will be **nullified** (zero credit) under the academic integrity rule, regardless of other completed work.
* The [`submission\\\_example/`](submission_example/) materials are a **reference only**. Use the folder organization and explanation style as references, but write your own API, database schema, tests, and presentation content. Do not submit copied example code, slides, or text as your own work.
* The example's API, authentication, database, and test implementations are intentionally omitted. Its documentation, sample responses, and example self-assessment scores illustrate a finished project; they do not establish that the supplied code works. Completing the example for practice is different from preparing your own submission.
* Implementation credit is based on working behavior, not merely the presence of files or a test-client screen. TODO placeholders, the example's HTTP 501 response, an SQL file that creates no tables, and tests that send no requests do not earn implementation credit for the corresponding functionality. Assess your actual work using the existing categories and weights below.
* Late work requires prior approval. Request an extension at least 48 hours before the deadline. An approved extension adds one week with a 20% deduction.

\---

## REST API Implementation (65%)

### Required items

* \[20/20%] Implemented at least **10 total REST APIs** in PHP/MySQL (**2% per API**).

  * Endpoints should demonstrate standard CRUD operations.
  * At least **2 of these 10 APIs** must use **Bearer Token authentication** (these 2 protected APIs count toward the total 10).
  * List the names of your implemented APIs (except for the Bearer Token APIs): GET, POST, PUT
* \[10/10%] Implemented at least **2 Bearer Token-protected APIs** in PHP/MySQL (**5% per API**).

  * Token authentication pattern should follow lecture/homework guidelines.
  * List the names of the Bearer Token APIs: DELETE
* \[10/10%] Created **cURL test code** for all implemented APIs.

  * List the file name: test_curl
* \[10/10%] Created **HTML/JavaScript test code** for all implemented APIs.

  * List the file name: test_client
* \[5/5%] Database integration works correctly with MySQL.

  * Full credit only if the database portion works correctly.
  * List your MySQL table names: Students & Courses
* \[10/10%] Code pushed to a **GitHub repository** with clear execution instructions.

  * **5%:** Required code is pushed to GitHub and a valid repository link is provided.
  * **5%:** Root `README.md` clearly explains prerequisites, database setup/schema import, server start commands (`http://localhost:8080`), and how to run tests. The grader can easily follow the instructions to run and test the project.
  * Write your GitHub repository link: https://github.com/luyizaa/project1
  * Write your GitHub repository README.md link: https://github.com/luyizaa/project1/blob/main/README.md

\---

|Task|%|Earned|
|-|-:|-:|
|At least 10 REST APIs|20%|\[ 20/20%]|
|At least 2 Bearer Token APIs|10%|\[ 10/10%]|
|cURL test code for all APIs|10%|\[ 10/10%]|
|HTML/JavaScript test code for all APIs|10%|\[ 10/10%]|
|MySQL database integration|5%|\[ 5/5%]|
|GitHub repository and execution guide|10%|\[ 10/10%]|
|**Total**|**65%**|\[ 65/65%]|

\---

## Tutorial Slides (25%)

### Required items

* \[15/15%] Created **Marp slides** explaining API usage.

  * Full credit requires **more than 15 slides/pages**, clear explanation, and meaningful coverage of the APIs.
  * Partial credit may be given for incomplete but substantial work.
  * No credit if no slide deck is submitted.
  * List Marp file name: api_tutorial.md
* \[5/5%] The marp slides are correctly transformed into pdf files.

  * List PDF file name: api_tutorial.pdf
* \[5/5%] Slides and PDFs uploaded to **GitHub**.

  * Write your GitHub repository Marp file link: https://github.com/luyizaa/project1/blob/main/presentation/api_tutorial.md
  * Write your GitHub repository PDF link: https://github.com/luyizaa/project1/blob/main/presentation/api_tutorial.pdf

\---

|Task|%|Earned|
|-|-:|-:|
|Marp slides explaining the APIs|15%|\[ 15/15%]|
|Slides correctly exported to PDF|5%|\[ 5/5%]|
|Slides and PDFs uploaded to GitHub|5%|\[ 5/5%]|
|**Total**|**25%**|\[ 25/25%]|

\---

## NGINX Deployment (10%)

### Required items

* \[5/5%] REST API successfully deployed with **NGINX**.

  * Full credit only if the deployment is working.
* \[5/5%] Deployment steps documented in **Marp slides** and exported to **PDF**.

  * Full credit requires a clear deployment tutorial.
  * Include screen captures that show the NGINX server working.
  * List Marp file name: nginx_deployment

\---

|Task|%|Earned|
|-|-:|-:|
|REST API deployed with NGINX|5%|\[ 5/5%]|
|Deployment tutorial in Marp and PDF|5%|\[ 5/5%]|
|**Total**|**10%**|\[ 10/10%]|

\---

## Final Submission Checklist

* \[X] I understand that the instructor may deduct percentage points for poor-quality or incomplete work.
* \[X] I understand that missing citations may be treated as plagiarism.
* \[X] I completed this individual project myself and can explain every part.
* \[X] I used AI only for permitted support and did not use vibe coding, and I understand my project will be nullified if AI-generated work is discovered.
* \[X] I used `submission\\\_example/` only as a reference for organization and explanation style; I wrote my own implementation and content, and my self-assessment reflects verified functionality rather than example placeholders or sample scores.
* \[X] I verified that my database initialization instructions and server start commands (`http://localhost:8080`) in `README.md` work properly.
* \[X] I copied files into the correct directories: `code/`, `presentation/`, `plan/`.
* \[X] I included this completed rubric file (`project1\\\_rubric.md`) and my `README.md` in the submission folder.
* \[X] I pushed (uploaded) the required materials to GitHub and included the repository link.
* \[X] I zipped my entire project1 submission folder — including this rubric file and `README.md` — and uploaded only that ZIP as my final Canvas submission.
* \[X] My project is organized clearly and is suitable for portfolio presentation.

\---

|Task|%|Earned|
|-|-:|-:|
|REST API Implementation|65%|\[ 65/65%]|
|Tutorial Slides|25%|\[ 25/25%]|
|NGINX Deployment|10%|\[ 10/10%]|
|**Total**|**100%**|\[ 100/100%]|



