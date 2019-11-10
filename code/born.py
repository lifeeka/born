from __future__ import print_function, unicode_literals


def main():
    import argparse
    import datetime
    import os
    import shutil
    import sys
    import yaml

    from colored import fg, attr
    from python_hosts import Hosts
    from PyInquirer import prompt

    import build
    import manage
    import status
    from creator import Creator

    parser = argparse.ArgumentParser()
    parser.add_argument("task", help="Task", type=str)
    parser.add_argument("sub_task", nargs='?', help="Task", type=str)
    parser.add_argument("-f", "--force", help="force create")
    parser.add_argument('-c', '--dmp', nargs='+')

    project = manage.Manage()
    args = parser.parse_args()
    try:
        if args.task == 'init':

            if args.sub_task == 'station':

                directories = [d for d in os.listdir(os.getcwd()) if os.path.isdir(d)]

                for filename in directories:
                    config_path = filename + '/.born//config.yml'

                    if os.path.isfile(config_path):
                        data = yaml.safe_load(open(config_path))

                        print("\n")
                        print('%s%s%s %s %s' % (
                        attr('bold'), fg('blue'), data['project-id'] + ": ", data['project-name'], attr(0)))
                        status = status.Status()
                        status.check_status(data['domains'][0])
                        print("\n")


            else:

                # check already initialized
                if project.is_initialized():
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
                        project.down()
                        shutil.rmtree('born')
                    elif alreadyCreatedAnswer == 'Backup and Create':
                        ts = datetime.datetime.now().timestamp()
                        shutil.move('born', 'born-bk-' + str(ts))

                # project name
                project_name = os.path.basename(os.getcwd())
                project_name_generate = [
                    {
                        'type': 'input',
                        'name': 'name',
                        'default': project_name,
                        'message': 'Project name:',
                    }
                ]
                project_name = prompt(project_name_generate)['name']

                init = Creator(project_name)
                init.create_php_service()
                init.create_expressjs_service()
                init.create_nginx_service()
                init.create_mariadb_service()
                init.create_mongodb_service()

                init.generate_docker_compose()
                init.generate_config()
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

                stack = [
                    {
                        'type': 'confirm',
                        'name': 'start',
                        'message': 'Start the docker compose:',
                    }
                ]
                answers = prompt(stack)
                if answers['start']:
                    project.up()
                    status = status.Status()
                    status.status()

        elif args.task == 'build':
            if args.sub_task == "force":
                build = build.Build('born')
                build.build(True)
            else:
                build = build.Build('born')
                build.build()

        elif args.task == 'up':
            project.up()

        elif args.task == 'down':
            project.down()

        elif args.task == 'restart':

            if args.sub_task == "-f":
                print("Stopping Containers...")
                command = os.popen('cd .born && docker-compose   -p ' + project.get_project_id() + ' down')
                print(command.read())

                print("Starting Containers")
                command = os.popen('cd .born && docker-compose  -p ' + project.get_project_id() + '  up -d')
                print(command.read())
            else:
                command = os.popen('cd .born && docker-compose  -p ' + project.get_project_id() + '  restart')
                print(command.read())
            command.close()

        elif args.task == 'status':
            status = status.Status()
            status.status()

        elif args.task == 'ls':

            if not args.sub_task:
                project.list()
            elif args.sub_task == 'domain':
                domain_list = Hosts()
                for domain in domain_list.entries:
                    print(domain)

        elif args.task == 'log':
            command = os.popen('cd .born && docker-compose logs -f ' + args.sub_task)
            print(command.read())
            command.close()

        elif args.task == 'login':
            command = os.system(
                'cd .born && docker-compose  -p ' + project.get_project_id() + ' exec ' + args.sub_task + ' sh')

        elif args.task == '-v':
            print("0.0.001")
            print("0.0.001")
        elif args.task == 'cmd':
            cmd = ' '.join(args.dmp)
            os.system('cd .born && docker-compose -p ' + project.get_project_id() + ' ' + cmd)
        else:
            print('%s Invalid Command %s' % (fg(1), attr(1)))

    except Exception as e:
        exc_type, exc_obj, exc_tb = sys.exc_info()
        file_name = os.path.split(exc_tb.tb_frame.f_code.co_filename)[0]
        print("Something wrong! ", str(e) + ":", file_name, exc_tb.tb_lineno)
