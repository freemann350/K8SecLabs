# K8SecLabs

A Kubernetes and Container based Cyber Range Laravel Control Panel

UI Based on my previous project, [MikroKontrol](https://https://github.com/freemann350/MikroKontrol).
    
## Architecture

![Overall architecture](https://github.com/freemann350/K8SecLabs/assets/25934321/3cf72a22-bca6-4a76-82dd-2e328e56ea77)
![Database](https://github.com/freemann350/K8SecLabs/assets/25934321/ede5e520-8f8d-4947-8538-8d245904b8c6)

## Notes

- Admins can do everything
- Professors can create definitions and environments
- Trainees can only join and access environments

## ToDo

- Revise DB tables and fields
- Revise Environment Access methods
---
- Database
  - [x] Migrations
  - [x] Seeders

- Frontend
  - Common
    - [x] Login
    - [ ] Reset password (?)
    - Edit own information
      - [x] Common information
      - [x] Password
  - Admins
    - Users
      - [x] Create
      - [x] Read
      - [x] Update (except password)
      - [x] Delete
      - [ ] Disallow admin to delete himself
    - Environments
      - [ ] Read
      - [ ] Delete
  - Professors
    - Categories
      - [x] Create
      - [x] Read
      - [x] Update
      - [x] Delete
    - Definitions
      - [x] Create
      - [x] Read
      - [x] Download
      - [x] Update
      - [x] Delete
      - [x] Add other professors definitions to themselves
    - Environments
      - [x] Create
      - [x] Read
      - [x] History
      - [ ] Update (?)
      - [x] Delete
    - Environments Access
      - [x] Read
      - [x] History
  - Trainees
      - Environments Access
        - [x] Access
        - [x] History
        - [x] Read

  - Extras
    - [ ] New scenarios
    - [ ] Better definition forms
    - [ ] Documentation for definition (Q&A)

  - ToDO (Fixes)
    - [ ] Verify if environment is ready (Pods have IP)
      1. Introduces code
      2. Verify each environment if status is running
      3. If not running, returns back (The selected environment is not ready yet. Please wait.)
      3. If running, variable {\*POD-IPLIST\*} (if set) will be replaced with pod IPs and their names
    - [ ] Change variables on access description