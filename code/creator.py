import os
import random
import shutil

import yaml
from PyInquirer import prompt
from nginx.config.api import Location

import env
import manage
import nginx_conf
from manage import Manage
from service import expressjs_service
from service import mariadb_service
from service import mongodb_service
from service import nginx_service
from service import php_service

from docker_files.expressjs import docker_text as docker_expressjs
from docker_files.mariadb import docker_text as docker_mariadb
from docker_files.mongodb import docker_text as docker_mongodb
from docker_files.nginx import docker_text as docker_nginx
from docker_files.php import docker_text as docker_php


class Creator:
    network = "born"
    directory = ".born"
    host_need = {}
    config = {
        "domains": []
    }
    docker_compose = {
        "services": {},
        "version": "3.7"
    }

    def __init__(self, project_name):
        self.project_name = project_name
        self.project = manage.Manage()
        os.mkdir(self.directory)

        self.file = self.directory + '/docker-compose.yml'
        self.config_file = self.directory + '/config.yml'
        self.env = env.Env(self.directory + '/.env')
        self.dir_path = os.path.dirname(os.path.realpath(__file__))

        # set project id
        self.config['project-id'] = project_name + '-' + str(random.getrandbits(32))
        self.config['project-name'] = project_name

        stack = [
            {
                'type': 'input',
                'name': 'timezone',
                'message': 'Timezone [UTC]:',
            }
        ]
        answers = prompt(stack)
        self.env.add_value("TZ", answers['timezone'] or 'UTC')

    def add_service(self, service_name, config):
        self.docker_compose['services'][service_name] = config

    def generate_docker_compose(self):
        with open(self.file, 'w') as file:
            yaml.dump(self.docker_compose, file, default_style=None, default_flow_style=False)

    def generate_config(self):
        with open(self.config_file, 'w') as file:
            yaml.dump(self.config, file, default_style=None, default_flow_style=False)

    def create_mongodb_service(self):
        stack = [
            {
                'type': 'confirm',
                'name': 'install',
                'default': False,
                'message': 'Install Mongo:',
            }
        ]
        answers = prompt(stack)

        if answers['install']:
            options = [{
                'type': 'list',
                'name': 'version',
                'default': '4.2',
                'message': 'Mongo Version:',
                'choices': [
                    '4.2'
                ]
            }]
            option_answers = prompt(options)

            mongodb_service_object = mongodb_service.MongodbService(self.project_name, self.env)
            mongodb_service_object.set_version(option_answers['version'])
            mongodb_service_object.create()

            # create directory
            os.mkdir(self.directory + '/mongodb')
            f = open(self.directory + '/mongodb/Dockerfile', "w+")
            f.write(docker_mongodb.get_code())
            f.close()

            self.add_service('mongodb', mongodb_service_object.get_config())

    def create_nginx_service(self):
        stack = [
            {
                'type': 'confirm',
                'name': 'install',
                'message': 'Install Nginx:',
            }
        ]
        answers = prompt(stack)

        if answers['install']:
            options = [{
                'type': 'list',
                'name': 'version',
                'default': 'alpine',
                'message': 'Nginx Version:',
                'choices': [
                    'alpine'
                ]
            }]
            option_answers = prompt(options)

            nginx_service_object = nginx_service.NginxService(self.project_name, self.env)
            nginx_service_object.set_version(option_answers['version'])
            nginx_service_object.create()

            # create directory
            os.mkdir(self.directory + '/nginx')
            f = open(self.directory + '/nginx/Dockerfile', "w+")
            f.write(docker_nginx.get_code())
            f.close()

            self.add_service('nginx', nginx_service_object.get_config())

            # domain
            for key in self.host_need:
                # create domain in host files
                create_domain = [
                    {
                        'type': 'confirm',
                        'name': 'install',
                        'message': 'You want to create a local domain for ' + key + '? :',
                    }
                ]
                if prompt(create_domain)['install']:
                    domain_details = [
                        {
                            'type': 'input',
                            'default': self.project_name + '.local',
                            'name': 'domain_name',
                            'message': 'Domain Name:',
                        },
                        {
                            'type': 'input',
                            'name': 'ip',
                            'default': '0.0.0.0',
                            'message': 'Ip Address:',
                        }
                    ]
                    # get port
                    random_port = self.project.get_random_port()
                    self.env.add_value("NGINX_PORT", random_port)

                    domain_details_answer = prompt(domain_details)
                    nginx_config = nginx_conf.NginxConf(self.project_name)
                    nginx_config.create_domain(domain_details_answer['domain_name'], domain_details_answer['ip'])

                    # create nginx conf file
                    config_data = nginx_config.create_server(domain_details_answer['domain_name'],
                                                             self.host_need[key][0], self.host_need[key][1])

                    if not os.path.isdir(self.directory + '/nginx/sites/'):
                        os.mkdir(self.directory + '/nginx/sites/')

                    file = open(self.directory + '/nginx/sites/' + domain_details_answer['domain_name'] + '.conf', 'w')
                    file.write(config_data)
                    file.close()

                    # add to config
                    self.config['domains'].append(domain_details_answer['domain_name'] + ":" + str(random_port))

    def create_mariadb_service(self):
        stack = [
            {
                'type': 'confirm',
                'name': 'install',
                'default': False,
                'message': 'Install Mariadb:',
            }
        ]
        answers = prompt(stack)

        if answers['install']:
            options = [{
                'type': 'list',
                'name': 'version',
                'default': '10.4',
                'message': 'MariaDB Version:',
                'choices': [
                    '10.4'
                ]
            }]
            option_answers = prompt(options)

            # port
            random_port = self.project.get_random_port()
            self.env.add_value("MARIADB_PORT", random_port)

            mariadb_service_object = mariadb_service.MariadbService(self.project_name, self.env)
            mariadb_service_object.set_version(option_answers['version'])
            mariadb_service_object.create()

            # create directory
            os.mkdir(self.directory + '/mariadb')
            f = open(self.directory + '/mariadb/Dockerfile', "w+")
            f.write(docker_mariadb.get_code())
            f.close()

            self.add_service('mariadb', mariadb_service_object.get_config())

    def create_php_service(self):
        stack = [
            {
                'type': 'confirm',
                'name': 'install',
                'message': 'Install PHP:',
            }
        ]
        answers = prompt(stack)

        if answers['install']:
            options = [{
                'type': 'list',
                'name': 'version',
                'default': '7.3',
                'message': 'PHP Version:',
                'choices': [
                    '7.3',
                    '7.2'
                ]
            }]
            option_answers = prompt(options)

            php_service_object = php_service.PhpService(self.project_name, self.env)
            php_service_object.set_version(option_answers['version'])
            php_service_object.create()

            # create directory
            os.mkdir(self.directory + '/php')
            f = open(self.directory + '/php/Dockerfile', "w+")
            f.write(docker_php.get_code())
            f.close()

            self.add_service('php', php_service_object.get_config())

            self.host_need['php'] = [Location('~ \.php$',
                                              try_files="$uri / index.php = 404",
                                              fastcgi_pass="php:9000",
                                              fastcgi_index="index.php",
                                              fastcgi_buffers="16 16k",
                                              fastcgi_buffer_size="32k",
                                              fastcgi_param="SCRIPT_FILENAME $document_root$fastcgi_script_name",
                                              fastcgi_read_timeout="600",
                                              include="fastcgi_params",

                                              ),
                                     Location('/', try_files='$uri /index.php$is_args$args', )]

    def create_expressjs_service(self):
        stack = [
            {
                'type': 'confirm',
                'name': 'install',
                'default': False,
                'message': 'Install ExpressJs:',
            }
        ]
        answers = prompt(stack)

        if answers['install']:
            options = [{
                'type': 'list',
                'name': 'version',
                'default': '4.17',
                'message': 'Express Version:',
                'choices': [
                    '4.17'
                ]
            }]
            option_answers = prompt(options)

            expressjs_service_object = expressjs_service.ExpressJsService(self.project_name, self.env)
            expressjs_service_object.set_version(option_answers['version'])
            expressjs_service_object.create()

            # create directory
            os.mkdir(self.directory + '/expressjs')
            f = open(self.directory + '/expressjs/Dockerfile', "w+")
            f.write(docker_expressjs.get_code())
            f.close()

            self.add_service('expressjs', expressjs_service_object.get_config())

            self.host_need['expressjs'] = [Location('/',
                                                    proxy_set_header="X-Forwarded-For $remote_addr",
                                                    proxy_pass="http://expressjs:3000",
                                                    ), Location('/src')]
