# Twig Globals

Logging Library provides the following global variables in your Twig templates.

## `loggingLibraryHelper`

*Provided by `lindemannrock/base`*

| Property | Description |
|----------|-------------|
| `loggingLibraryHelper.displayName` | Display name (singular, without "Manager") |
| `loggingLibraryHelper.pluralDisplayName` | Plural display name (without "Manager") |
| `loggingLibraryHelper.fullName` | Full plugin name (as configured) |
| `loggingLibraryHelper.lowerDisplayName` | Lowercase display name (singular) |
| `loggingLibraryHelper.pluralLowerDisplayName` | Lowercase plural display name |

### Examples

```twig
{{ loggingLibraryHelper.displayName }}
{{ loggingLibraryHelper.pluralDisplayName }}
{{ loggingLibraryHelper.fullName }}
{{ loggingLibraryHelper.lowerDisplayName }}
{{ loggingLibraryHelper.pluralLowerDisplayName }}
```

---

