from PyInquirer import prompt
from colorama import Fore
from colored import attr, fg
from nginx.config.api import Config, Section, Location
from past.builtins import raw_input
from python_hosts import HostsEntry, Hosts


class NginxConf:

    def __init__(self, project_name):
        self.project_name = project_name

    def create_server(self, domain='0.0.0.0', location={}):



        server = Section(
            'server',
            Location('/', try_files='$uri /index.php$is_args$args', ),
            location,
            access_log='/var/log/nginx/error.log',
            error_log='/var/log/nginx/access.log',

            server_name=domain,
            listen="80 default_server",
            root="/app/public")

        return str(server)

    def create_domain(self, domain, ip):

        if len(domain):
            hosts = Hosts()
            new_host = HostsEntry(entry_type='ipv4',
                                  address=ip,
                                  names=[domain])

            hosts.add([new_host])
            hosts.write()
        else:
            print('%s%s %s %s' % (fg('red'), attr('bold'), 'Empty Domain', attr(0)))
