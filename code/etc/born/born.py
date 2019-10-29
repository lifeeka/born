from __future__ import print_function, unicode_literals

import datetime
import os
import sys
import shutil

from PyInquirer import prompt
from pprint import pprint
import argparse
from creator import Creator
from colored import fg, bg, attr
from python_hosts import Hosts, HostsEntry
import build

parser = argparse.ArgumentParser()
parser.add_argument("task", help="Task", type=str)
parser.add_argument("sub_task", nargs='?', help="Task", type=str)
parser.add_argument("-f", "--force", help="force create")

args = parser.parse_args()

if args.task == 'init':

    # check already initialized
    if os.path.isdir("born"):
        alreadyCreated = [
            {
                'type': 'list',
                'name': 'action',
                'message': 'Born is already been initialized:',
                'choices': [
                    'Close',
                    'Re Create',
                    'Backup and Create'
                ]
            }
        ]
        alreadyCreatedAnswer = prompt(alreadyCreated)['action']

        if alreadyCreatedAnswer == 'Close':
            sys.exit()
        elif alreadyCreatedAnswer == 'Re Create':
            shutil.rmtree('born')
        elif alreadyCreatedAnswer == 'Backup and Create':
            ts = datetime.datetime.now().timestamp()
            shutil.move('born', 'born-bk-' + str(ts))

    init = Creator('born')
    init.create_php_service()
    init.create_expressjs_service()
    init.create_nginx_service()
    #init.create_mariadb_service()
    #init.create_mongodb_service()

    init.generate_docker_compose()
    stack = [
        {
            'type': 'confirm',
            'name': 'build',
            'message': 'Build the docker compose:',
        }
    ]
    answers = prompt(stack)
    if answers['build']:
        build = build.Build('born')
        build.build(True)


elif args.task == 'build':
    if args.sub_task == "force":
        build = build.Build('born')
        build.build(True)
    else:
        build = build.Build('born')
        build.build()
elif args.task == 'up':
    command = os.popen('cd born && docker-compose up -d')
    print(command.read())
    command.close()
elif args.task == 'down':
    command = os.popen('cd born && docker-compose down')
    print(command.read())
    command.close()
elif args.task == 'restart':

    if args.sub_task == "-f":
        print("Stopping Containers...")
        command = os.popen('cd born && docker-compose down')
        print(command.read())

        print("Starting Containers")
        command = os.popen('cd born && docker-compose up -d')
        print(command.read())
    else:
        command = os.popen('cd born && docker-compose restart')
        print(command.read())
    command.close()
elif args.task == 'ls':

    if not args.sub_task:
        command = os.popen('cd born && docker-compose ps')
        print('%s%s %s %s' % (fg('blue'), attr('bold'), command.read(), attr(0)))
        command.close()
    elif args.sub_task == 'domain':
        domain_list = Hosts()
        for domain in domain_list.entries:
            print(domain)

elif args.task == 'log':
    command = os.popen('cd born && docker-compose logs -f ' + args.sub_task)
    print(command.read())
    command.close()
elif args.task == 'login':
    command = os.system('cd born && docker-compose exec ' + args.sub_task + ' sh')
elif args.task == '-v':
    print("0.0.001")
    print("0.0.001")
else:
    print('%s Invalid Command %s' % (fg(1), attr(1)))
