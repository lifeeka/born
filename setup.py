from setuptools import setup

setup(
    name='file',
    include_package_data=True,
    install_requires=[
        'pyyaml',
        'colored',
        'python-hosts',
        'pyinquirer',
        'request',
        'requests',
        'nginx-config-builder',
        'colorama'
    ],
)
