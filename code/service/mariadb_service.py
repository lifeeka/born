class MariadbService:
    version = '${MARIADB_VERSION}'
    time_zone = '${TZ}'
    port = '${MARIADB_PORT}'
    network = 'born'
    config = {}

    def __init__(self, project_name, env):
        self.project_name = project_name
        self.env = env

    def set_version(self, version):
        self.env.add_value("MARIADB_VERSION", version)

    def set_network(self, network):
        self.network = network

    def create(self):
        self.config = {
            "environment": {
                "MYSQL_ROOT_PASSWORD": "root"
            },
            "ports": [
                 "" + str(self.port) + ":3306"
            ],
            "volumes": [
                "./data/mariadb/:/var/lib/mysql"
            ],
            "build": {
                "args": {
                    "TZ": self.time_zone,
                    "MARIADB_VERSION": self.version,
                },
                "context": "mariadb"
            }
        }

    def get_config(self):
        return self.config
