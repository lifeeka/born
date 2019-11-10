class MongodbService:
    version = '${MONGODB_VERSION}'
    time_zone = '${TZ}'
    network = 'born'
    config = {}

    def __init__(self, project_name, env):
        self.project_name = project_name
        self.env = env

    def set_version(self, version):
        self.env.add_value("MONGODB_VERSION", version)

    def set_network(self, network):
        self.network = network

    def create(self):
        self.config = {
            "ports": [
                "50001:3306"
            ],
            "volumes": [
                "./data/mongodb/:/data/db"
            ],
            "build": {
                "args": {
                    "TZ": self.time_zone,
                    "MONGODB_VERSION": self.version,
                },
                "context": "mongodb"
            }
        }

    def get_config(self):
        return self.config
