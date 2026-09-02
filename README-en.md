# Upfront Builder

[**Deutsch**](README.md) | **English**

[![Version](https://img.shields.io/badge/Version-1.0.5-2271b1?style=flat-square)](readme.txt)
![PHP](https://img.shields.io/badge/PHP-8.0%2B-777bb4?style=flat-square&logo=php&logoColor=white)
![WordPress](https://img.shields.io/badge/WordPress-up%20to%207.1.0-21759b?style=flat-square&logo=wordpress&logoColor=white)
![ClassicPress](https://img.shields.io/badge/ClassicPress-2.7.1-03768e?style=flat-square)
[![License](https://img.shields.io/badge/License-GPL--2.0--or--later-2ea44f?style=flat-square)](https://www.gnu.org/licenses/gpl-2.0.html)

**Visual theme development for ClassicPress.**

Upfront Builder extends the Upfront Framework with a complete workspace for creating, editing, and exporting custom themes. Layouts, global design rules, and responsive views are designed directly on the website. The result is not a page design locked inside the database, but a standalone Upfront theme that can be distributed and used on other installations.

> Upfront Builder works with Upfront-based themes. It is not a universal page builder for arbitrary ClassicPress themes.

## What the Builder can do

- create new Upfront themes through a guided setup
- develop existing Upfront themes visually
- manage page, post, archive, search, and 404 layouts
- position content and design elements using drag and drop
- create responsive layouts with breakpoints and grid guides
- maintain global colors, typography, regions, and element styles
- use images, galleries, sliders, videos, menus, forms, widgets, and custom code
- edit theme metadata, preview image, license, and text domain
- write layouts, styles, and optionally used images to the theme
- download completed themes as installable packages
- edit design foundations using the integrated CodePen style guide

## How it works

### 1. Prepare Upfront

The Builder requires the Upfront Framework and an active Upfront-based theme. If the Framework is missing, the integrated Kickstart automatically downloads the theme package from the [latest stable GitHub release](https://github.com/Power-Source/upfront/releases), verifies its SHA-256 integrity, installs it, and activates it. If Upfront is already installed but inactive, Kickstart only handles activation. Pretty permalinks must also be enabled.

### 2. Create or select a theme

In the ClassicPress administration area, open **Upfront > Upfront Builder**. From there, you can:

- name a new theme and start building immediately,
- open an existing Upfront theme in the Builder,
- edit theme information and its preview image, or
- download a completed theme.

When creating a theme, the Builder generates the required theme structure and provides essential layout templates.

### 3. Design visually

The Builder opens within the frontend context of the website. This lets you edit layouts and regions directly in the environment where they will eventually appear. Elements can be added, moved, resized, and configured; responsive variants are adjusted through the breakpoint system.

Changes made in Builder mode are saved to the files of the selected theme. This makes the workflow suitable for both rapid prototypes and distributable client or starter themes.

### 4. Save and export

Using **Save theme**, the Builder writes layouts and design information to the theme. In theme management, you can then complete the metadata, include images in the export, and download the complete theme package. The exported theme can be installed like any other ClassicPress theme, provided that the Upfront Framework is available on the target system.

## Typical workflow

1. Install and activate Upfront Builder.
2. Enable pretty permalinks.
3. If necessary, let Kickstart install and activate the Upfront Framework.
4. Create or select a theme under **Upfront > Upfront Builder**.
5. Define the base layout, global styles, and reusable regions.
6. Design single, archive, and special-purpose pages and verify their responsive behavior.
7. Complete the theme information and preview image.
8. Save and download the theme, then test it on a separate installation.

## Requirements

- ClassicPress
- PHP 7.0 or later
- the Upfront Framework, or outbound HTTPS access to GitHub for automatic installation
- an active Upfront-based theme after Kickstart has completed
- pretty permalinks enabled
- administrator permissions for setup and export

Developing the plugin additionally requires Node.js 20.19 or later, npm 10 or later, and WP-CLI.

## Installation

1. Copy the `upfront-builder` directory to `wp-content/plugins/`, or upload the plugin package through plugin management.
2. Activate **Upfront Builder** in ClassicPress.
3. If Upfront is missing, select **Install Framework and start Builder** in the displayed notice. The Builder exclusively uses the official `upfront.zip` asset from the latest stable release.
4. Select a pretty permalink structure under **Settings > Permalinks**.
5. Open **Upfront > Upfront Builder**.

## Local runtime libraries

The Builder loads its runtime libraries from the plugin itself. Interact.js provides drag-and-drop and resizing behavior; DOMPurify sanitizes server-provided form views before they are rendered. No CDN resources are required for these features.

The respective license texts are stored alongside the libraries in the [`vendor`](vendor/) directory.

## Development

Install dependencies and run all checks:

```bash
npm ci
npm test
```

Rebuild the CSS:

```bash
npm run build
```

All prerequisites, commands, and translation workflow notes are documented in [DEVELOPMENT.md](DEVELOPMENT.md). Changes between releases are listed in [CHANGELOG.md](CHANGELOG.md).

## Documentation

- [Upfront Builder documentation](https://psource.eimen.net/wiki/upfront-dokumentation/upfront-builder-dokumentation/)
- [Upfront theme development](https://psource.eimen.net/wiki/upfront-themes/upfront-theme-entwickler/)
- [Project page](https://cp-psource.github.io/upfront-builder/)

## License

Upfront Builder is free software licensed under the [GNU General Public License, version 2 or later](license.txt).

Copyright 2014-2026 [PSOURCE](https://psource.eimen.net/)
