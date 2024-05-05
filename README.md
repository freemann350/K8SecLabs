# K8SecLabs

A Kubernetes and Container based Cyber Range Laravel Control Panel

UI Based on my previous project, [MikroKontrol](https://https://github.com/freemann350/MikroKontrol).

## ToDo

**Notes**

- Admins can do everything
- Professors can create definitions and environments
- Trainees can only join and access environments

- Revise DB tables and fields
- Revise Environment Access methods

- Database
  - [x] Migrations
  - [x] Seeders

- Frontend
  - Common
    - [ ] Login
    - [ ] Reset password (?)
    - Edit own information
      - [ ] Common information
      - [ ] Password
  - Admins
    - Users
      - [ ] Create
      - [ ] Read
      - [ ] Update (except password)
      - [ ] Delete
    - Environments
      - [ ] Read
      - [ ] Delete
  - Professors
    - Categories
      - [ ] Create
      - [ ] Read
      - [ ] Update
      - [ ] Delete
    - Definitions
      - [ ] Create
      - [ ] Read
      - [ ] Update
      - [ ] Delete
      - [ ] Add other professors definitions to themselves
    - Environments
      - [ ] Create
      - [ ] Read
      - [ ] History
      - [ ] Update (?)
      - [ ] Delete
    - Environments Access
      - [ ] Read
      - [ ] History
  - Trainees
      - Environments Access
        - [ ] Access
        - [ ] History
        - [ ] Read

- Backend
  - [ ] Select kubernetes distribution
  - [ ] Connection to the cluster
  - [ ] API Authentication
  - Deploying environments
    - [ ] Create Namespace
    - [ ] Create Pods
  - Deleting environments
    - [ ] Delete Namespace (assuming it will also delete all pods)