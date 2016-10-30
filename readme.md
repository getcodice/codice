# Codice

[![Build Status](https://travis-ci.com/Sobak/Codice.svg?token=56jJWzdqW9ZYp8m68yDz&branch=master)](https://travis-ci.com/Sobak/Codice)
[![Version](https://img.shields.io/badge/version-v0.4.2-blue.svg)](https://github.com/Sobak/Codice/releases)
![Developed Version](https://img.shields.io/badge/developed-v0.5.0--dev-orange.svg)

> **Codice** is online note-taking and task-management application.

![Codice screenshot](http://codice.eu/screenshot.png)

**[Project homepage](http://codice.eu)** | **[Documentation](http://docs.codice.eu)** | **[Downloads](https://github.com/Sobak/Codice/releases)**

## Features
- organizing notes using labels
- assigning deadline to make note a task
- indicating upcoming or overdue tasks by the color
- calendar view
- Markdown support
- API for writing plugins

## Get Codice
1. **[Download latest version](https://github.com/Sobak/Codice/releases)**
2. Upload the files
3. Open `yourdomain.com/codice/install` in the browser and follow the instructions

If you are more advanced user, read [installation chapter](http://docs.codice.eu/) in
the docs, so that you can build Codice from sources and install it step by step, having
full control over the process.

## Development with Docker
First, get a working `.env` file (probably using SQLite, server-ish RDMBS not included).

Build the container:

```bash
docker build -t codice .
```

Start it, detached (so you don't need to open a second terminal/console to stop it later):

```bash
docker run --publish 8000:8000 --name codice --detach codice
```

And navigate to http://localhost:8000/. After you finish work, shutdown the container:

```bash
docker stop codice
```

## License
The project is lincensed under MIT, check out [LICENSE](LICENSE.md) for more details.
