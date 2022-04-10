# Basic Rules

Some basic rules for myself I can update and refer to as needed.  
Living document that will be updated (in separate commits) as new ideas are formed.

### Commits

Should take the form slightly modified from the Angular standards.  
New types and scopes shouldn't be added until they are used.

```
<type>(<scope>): <short summary>

A more detailed message expanding on the summary, separated by empty lines.

Scope and detailed messages are optional when the short summary is clear enough on
smaller commits. This should be avoided and not the norm.

-----
If a footer is useful it will be preceded by (5) hyphens.
```

```
<type>(<scope>): <short summary>
│       │             │
│       │             └─⫸ Summary in present tense. Capitalized. No period at the end.
│       │
│       └─⫸ Commit Scope: Design|Docs
│
└─⫸ Commit Type: New|Refactor
```

## Front End

### Design

Ideally this will be 100% accomplished through default bootstrap classes because I want to implement a theme switcher
using ones provided by https://bootswatch.com/.

There is an `app.css` file that can be used to override/create classes if necessary. If done so, it should only be
changes related to size and spacing, and not colors due to multiple themes.

## Back End

### Global Functions/Helpers

When needed, they will live in `bootstrap/app.php` in a section marked so.

## Code Conventions

When adding comment blocks to break classes into sections:

```
/*
|--------------------------------------------------------------------------
| Section Title
|--------------------------------------------------------------------------
*/
```
