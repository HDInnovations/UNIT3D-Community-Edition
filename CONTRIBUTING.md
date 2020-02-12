# Contributing

Contributions are **welcome**.

We accept contributions via Pull Requests on [Github](https://github.com/HDInnovations/UNIT3D).

**Working on your first Pull Request?** You can learn how from this *free* series [How to Contribute to an Open Source Project on GitHub](https://egghead.io/series/how-to-contribute-to-an-open-source-project-on-github)\

## Commits

**Commit Title Standard**

Please use the following title schema. 
- prefix: Title

Examples:
- update: French Translations
- fix: French Translations
- security fix: French Translations
- remove: French Translations
- add: French Translations
- revert: French Translations
- refactor: French Translations

https://www.conventionalcommits.org/en/v1.0.0/

## Pull Requests

**PR Title Standard**

Please use the following title schema. 
- (PREFIX) Title

Examples:
- (Update) French Translations
- (Fix) French Translations
- (Security Fix) French Translations
- (Remove) French Translations
- (Add) French Translations
- (Revert) French Translations
- (Refactor) French Translations

**[PSR-2 Coding Standard](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)** 
- Check the code style with ``$ composer check-style`` and fix it with ``$ composer fix-style``.

**[Follow Laravel naming conventions](https://github.com/alexeymezenin/laravel-best-practices/blob/master/README.md#do-not-get-data-from-the-env-file-directly)**

What | How | Good | Bad
------------ | ------------- | ------------- | -------------
Controller | singular | ArticleController | ~~ArticlesController~~
Route | plural | articles/1 | ~~article/1~~
Named route | snake_case with dot notation | users.show_active | ~~users.show-active, show-active-users~~
Model | singular | User | ~~Users~~
hasOne or belongsTo relationship | singular | articleComment | ~~articleComments, article_comment~~
All other relationships | plural | articleComments | ~~articleComment, article_comments~~
Table | plural | article_comments | ~~article_comment, articleComments~~
Pivot table | singular model names in alphabetical order | article_user | ~~user_article, articles_users~~
Table column | snake_case without model name | meta_title | ~~MetaTitle; article_meta_title~~
Foreign key | singular model name with _id suffix | article_id | ~~ArticleId, id_article, articles_id~~
Primary key | - | id | ~~custom_id~~
Migration | - | 2017_01_01_000000_create_articles_table | ~~2017_01_01_000000_articles~~
Method | camelCase | getAll | ~~get_all~~
Method in resource controller | [table](https://laravel.com/docs/master/controllers#resource-controllers) | store | ~~saveArticle~~
Method in test class | camelCase | testGuestCannotSeeArticle | ~~test_guest_cannot_see_article~~
Variable | camelCase | $articlesWithAuthor | ~~$articles_with_author~~
Collection | descriptive, plural | $activeUsers = User::active()->get() | ~~$active, $data~~
Object | descriptive, singular | $activeUser = User::active()->first() | ~~$users, $obj~~
Config and language files index | snake_case | articles_enabled | ~~ArticlesEnabled; articles-enabled~~
View | snake_case | show_filtered.blade.php | ~~showFiltered.blade.php, show-filtered.blade.php~~
Config | snake_case | google_calendar.php | ~~googleCalendar.php, google-calendar.php~~
Contract (interface) | adjective or noun | Authenticatable | ~~AuthenticationInterface, IAuthentication~~
Trait | adjective | Notifiable | ~~NotificationTrait~~

- **Document any change in behaviour** - Make sure the `README.md` and any other relevant documentation are kept up-to-date.

- **Create feature branches** - Don't ask us to pull from your master branch.

- **One pull request per feature** - If you want to do more than one thing, send multiple pull requests.

- **Use Laravel helpers when possible and not facades** - auth(), info(), cache(), response(), ext. [Laravel Helpers](https://laravel.com/docs/5.6/helpers)

- **Use shortened syntax when possible** - Example: `[]` and not `array()`.



**Happy coding**!
