# K8SecLabs

A Kubernetes and Container based Cyber Range Laravel Control Panel.

UI Based on my previous project, [MikroKontrol](https://https://github.com/freemann350/MikroKontrol).

## Architecture

![Overall architecture](https://github.com/freemann350/K8SecLabs/assets/25934321/3cf72a22-bca6-4a76-82dd-2e328e56ea77)
(to be updated)

![Database](https://github.com/freemann350/K8SecLabs/assets/25934321/e6f92795-8c5a-4a64-b679-52af85beb506)
  
## Features

- User Management
- Categories
- Definition Management
- Environment creation and deletion
- Join environments

## Notes

- This is purely an academic project
- Roles:
	- Admins can do everything
	- Lecturers can create definitions and environments
	- Trainees can only join and access environments

## Current Scenarios Available

- [Simple OWASP Juice Shop](https://owasp.org/www-project-juice-shop/)
- [Kali VNC](https://github.com/freemann350/kali-docker) with OWASP Juice Shop
- [Simple CTF using Python Flask ](https://github.com/freemann350/simple-flask-ctf)
- Brute forcing SSH using  [Alpine SSH](https://hub.docker.com/r/woahbase/alpine-ssh/) and [Kali VNC](https://github.com/freemann350/kali-docker)

Undergoing development:
- MITM

## Deployment

Deployment similar to my previous project, K8Supervisor, [check the deployment guide](https://github.com/freemann350/K8Supervisor?tab=readme-ov-file#deployment-for-testing).