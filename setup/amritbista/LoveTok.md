
[LovTok](https://app.hackthebox.com/challenges/lovetok) is a web challenge which I installed in my machine. 

## CHALLENGE DESCRIPTION
True love is tough, and even harder to find. Once the sun has set, the lights close and the bell has rung... you find yourself licking your wounds and contemplating human existence. You wish to have somebody important in your life to share the experiences that come with it, the good and the bad. This is why we made LoveTok, the brand new service that accurately predicts in the threshold of milliseconds when love will come knockin' (at your door). Come and check it out, but don't try to cheat love because love cheats back. 💛

![[Pasted image 20230601141410.png]]
## Getting Started
- Firstly went through download files and downloaded the required files.
- Then extracted the files using the password `hackthebox`.
- Extracting the files resulted  in a folder with sub files and folders.
## Setup
The content of each files can be viewed with cat `filesname`.
Inside of the folder,
- `sudo ./build_docker.sh` was used to run the instance
- localhost:1337 opens up the challange file
## Files
### builddocker.sh
```shell
#!/bin/bash
docker rm -f lovetok
docker build -t lovetok . && \
docker run --name=lovetok --rm -p1337:80 -it lovetok
```
- First line indicates the script is to be executed with bash
- `docker rm -f lovetok` indicates to remove any running instance
- `docker buld -t lovetok . && \` builds a Docker image with the tag "lovetok" using the Dockerfile located in the current directory [.] . The -t option assigns the specified tag to the image. The `&&` operator is used to execute the next command (docker run) only if the previous command (docker build) succeeds. The backslash (\) is used to split the command across multiple lines for readability.
- `docker run --name=lovetok --rm -p1337:80 -it lovetok` runs a Docker container using the  "lovetok" image. 
- `--name=lovetok`sets the name of the container to "lovetok".
- `--rm`  remove the container when it exits. 
- `-p1337:80` allows to access port 1337 on the host machine, and will forward to port 80 inside the container `
- `-it` allows to interact with the container's command line if it provides an interactive shell.
- the `lovetok` at end means the name of docker image

### challenges
This folder contains the source code of the website that the docker file runs

### config
This is another folder, It has all the config file for the FPM, Nginx, and Supervisord components of the LoveTok web application. 
- `fpm.conf`: It contains configuration directives for PHP-FPM's behavior and settings.
- `nginx.conf`: It contains the main configuration file for the Nginx web server, and it typically includes various directives that control Nginx's behavior.
- `supervisord.conf`:The supervisord.conf file defines how supervisor manages and monitors various processes on the system. The supervisord.conf file is the main configuration file for Supervisor, a process control system for Unix-like systems. 

### Dockerfile
It contains a set of instructions used to build a Docker image.
- `FROM debian:buster-slim`: This line specifies the base image for your Docker image. In this case, it uses the Debian Buster Slim image as the starting point.
- `RUN useradd www`: This command creates a new user named "www" within the Docker image.
- `RUN apt-get update && apt-get install -y supervisor nginx lsb-release wget`: This line updates the package repositories and installs the following packages inside the Docker image: Supervisor, Nginx, lsb-release, and wget.
- `RUN wget -O /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg`: This command retrieves the GPG key required for adding the PHP repository.
- `RUN echo "deb https://packages.sury.org/php/ $(lsb_release -sc) main" | tee /etc/apt/sources.list.d/php.list`: This line adds the PHP repository to the package sources list using the tee command.
- `RUN apt update && apt install -y php7.4-fpm`: This command updates the package repositories again and installs PHP 7.4 FPM (FastCGI Process Manager) package.
- `COPY config/fpm.conf /etc/php/7.4/fpm/php-fpm.conf`: This line copies the fpm.conf file from the local config directory to the /etc/php/7.4/fpm/ directory inside the Docker image. It is used to configure PHP-FPM.
- `COPY config/supervisord.conf /etc/supervisord.conf`: This line copies the supervisord.conf file from the local config directory to the /etc/ directory inside the Docker image. It is used to configure Supervisor, the process control system.
- `COPY config/nginx.conf /etc/nginx/nginx.conf`: This line copies the nginx.conf file from the local config directory to the /etc/nginx/ directory inside the Docker image. It is used to configure Nginx, the web server.
- `COPY challenge /www`: This line copies the contents of the challenge directory from the local context to the /www directory inside the Docker image. It likely includes the files required for your application.
- `COPY flag /`: This line copies the flag file from the local context to the root directory (/) inside the Docker image. The purpose of this file may vary depending on the application or challenge being built.
- `RUN chown -R www:www /www /var/lib/nginx`: This command changes the ownership of the /www and /var/lib/nginx directories to the user and group "www" created earlier.
- `EXPOSE 80`: This line specifies that the Docker container will listen on port 80, allowing incoming connections to the Nginx web server.
- `COPY --chown=root entrypoint.sh /entrypoint.sh`: This line copies the entrypoint.sh script to the root directory (/) inside the Docker image and sets the ownership to the root user.
- `ENTRYPOINT ["/entrypoint.sh"]`: This line specifies the entry point command for the Docker container, which is the entrypoint.sh script. It will be executed when the container starts.
- `CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]`: This line specifies the default command to run when the container starts if no other command is provided. It runs Supervisor with the specified configuration file.

### Flag
It provides the flag for testing.

### entrypoint.sh
- `chmod 600 /entrypoint.sh`: This line sets the file permissions of the entrypoint.sh script to read and write for the owner only (600). This is a security measure to restrict access to the script.
- `FLAG=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 5 | head -n 1)`: This line generates a random string of 5 alphanumeric characters and assigns it to the FLAG variable. It uses the /dev/urandom device file to obtain random data, filters out non-alphanumeric characters using tr, and selects the first 5 characters using fold and head commands.
- `mv /flag /flag$FLAG`: This line renames the file /flag to /flag$FLAG, where $FLAG is the random string generated in the previous line. It effectively appends the random string to the original filename.
- `exec "$@"`: This line executes the command-line arguments passed to the entrypoint script. The "$@" expands to all the arguments provided when running the container. This allows you to specify different commands or scripts to run within the container at runtime.