# Jump Start WordPress Theme

This project is meant to take a unique approach to WordPress theme development, by utilizing Node.js and Gulp.

## Project Abstract

The Jump Start WordPress theme is a powerful, multi-purpose WordPress theme. Jump Start attempts to solve the dilemma of being a solid WordPress theme for both developers and end-users. The theme is extremely extendable from child themes and plugins. It comes with many end-user features, but many of these can be turned off by developers.

## Project Structure

* `dist/`
	* `jumpstart/`
	* `jumpstart-x.x.x.zip`
* `lib/`
	* `fontawesome/`
		* *Mirror of the current FontAwesome 5 distributed package.*
	* `frontstreet/`
		* *Coming in [3.0](https://github.com/themeblvd/jumpstart/milestone/24). Mirror of [Front Street](https://github.com/themeblvd/frontstreet) `src`.*
	* `plugin-manager/`
		* *Custom build of [My Plugin Manager](https://github.com/themeblvd/my-plugin-manager) for the Theme Blvd WordPress Framework.*
	* `themeblvd/`
		* *All php and asset files for the Theme Blvd WordPress Framework.*
* `src/`
	* *All php and asset files, specific to the Jump Start theme.*

This project structure is meant to help solve the following:

* **Less compiled assets.** By keeping the uncompiled versions of the theme, theme framework, and frontend framework in separate directories, they can remain separate and organized, but also allow the final WordPress theme to include many less CSS and JavaScript files.
* **Simple inclusion of [Front Street](https://github.com/themeblvd/frontstreet).** Front Street is our frontend framework, meant to be a replacement for [Bootstrap](https://github.com/twbs/bootstrap). To accomplish the previous task, we can not port in Front Street after it's compiled. So by mirroring the project's `/src` directory we can setup a good workflow for managing Front Street in its own separate repo, but still be able to easily port future updates in here.
* **True separation of Theme Blvd framework and theme.** In the past this was always done by separating out framework functionality into a `/framework` directory of the theme. However, as the theme framework has grown, many files outside of that directory are considered part of the "framework." This project structure will help to port the "framework" to other theme projects without having to remember specific files that were changed outside of the `/framework` directory.
* **Better distribution workflow.** When publishing an update, hopefully a few steps can be saved by automating the final release. The `/dist` directory will contain a copy of the final theme, and then that will also be zipped automatically into the final installable WordPress theme zip.

**Note: We do push final build changes in the `/dist` directory with each commit, in parallel with source changes. This is a bit of an uncommon practice, but we do it for those downloading previous releases that do not have the required experience with NodeJS or npm to run the project build.**

## Project Requirements

* [Theme Project Plugin](https://github.com/themeblvd/theme-project)
* Development URL setup as `http://{localhost URL}/themes/{name}`
