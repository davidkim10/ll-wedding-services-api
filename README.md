# Wedding Services API Plugin

This WordPress plugin offers API endpoints to retrieve comprehensive information about wedding services and packages available on the website.

- **Service pages:** Defined as any page nested under `/service/` within CMS (WordPress).
- **Wedding packages:** Defined as packages made from pricing tables found on the wedding pages
- **Wedding page IDs:** [2020, 1765, 381]

## Routes

| Route Path                            | Method | Description                              |
| ------------------------------------- | ------ | ---------------------------------------- |
| `/wp-json/custom/v1/services`         | GET    | Retrieves data for all services pages.   |
| `/wp-json/custom/v1/services/wedding` | GET    | Fetches data for wedding services pages. |
| `/wp-json/custom/v1/packages`         | GET    | Gets information about service packages. |

<div style="height: 50px;"></div>

### Services: `/custom/v1/services`

The endpoint `/custom/v1/services` returns JSON data providing information about the service pages. It mirrors the data structure of [WordPress' Pages API](https://developer.wordpress.org/rest-api/reference/pages/#schema), offering similar data:

| Field              | Type    | Description                                                  | Context           |
| ------------------ | ------- | ------------------------------------------------------------ | ----------------- |
| date               | string  | The date the post was published, in the site's timezone.     | view, edit, embed |
| date_gmt           | string  | The date the post was published, as GMT.                     | view, edit        |
| guid               | object  | The globally unique identifier for the post.                 | view, edit        |
| id                 | integer | Unique identifier for the post.                              | view, edit, embed |
| link               | string  | URL to the post.                                             | view, edit, embed |
| modified           | string  | The date the post was last modified, in the site's timezone. | view, edit        |
| modified_gmt       | string  | The date the post was last modified, as GMT.                 | view, edit        |
| slug               | string  | An alphanumeric identifier for the post unique to its type.  | view, edit, embed |
| status             | string  | A named status for the post.                                 | view, edit        |
| type               | string  | Type of post.                                                | view, edit, embed |
| password           | string  | A password to protect access to the content and excerpt.     | edit              |
| permalink_template | string  | Permalink template for the post.                             | edit              |
| generated_slug     | string  | Slug automatically generated from the post title.            | edit              |
| parent             | integer | The ID for the parent of the post.                           | view, edit        |
| title              | object  | The title for the post.                                      | view, edit, embed |
| content            | object  | The content for the post.                                    | view, edit        |
| author             | integer | The ID for the author of the post.                           | view, edit, embed |
| excerpt            | object  | The excerpt for the post.                                    | view, edit, embed |
| featured_media     | integer | The ID of the featured media for the post.                   | view, edit, embed |
| comment_status     | string  | Whether or not comments are open on the post.                | view, edit        |
| ping_status        | string  | Whether or not the post can be pinged.                       | view, edit        |
| menu_order         | integer | The order of the post in relation to other posts.            | view, edit        |
| meta               | object  | Meta fields.                                                 | view, edit        |
| template           | string  | The theme file to use to display the post.                   | view, edit        |

Please refer to the [WordPress Pages API Schema Details](https://developer.wordpress.org/rest-api/reference/pages/#schema) for more detailed information on each field.

This endpoint aims to retrieve and present data in a manner consistent with the WordPress Pages API, ensuring compatibility and familiarity with standard WordPress data structures.

<div style="height: 50px;"></div>

### Wedding: `/custom/v1/services/wedding`

The endpoint `/v1/services/wedding` returns JSON data containing information about specific wedding service pages. The response structure includes an array of objects, each representing a distinct wedding service page. Each object comprises the following fields:

| Field   | Type   | Description                                         |
| ------- | ------ | --------------------------------------------------- |
| id      | number | Unique identifier for the wedding service page.     |
| link    | string | URL link to the respective wedding service page.    |
| slug    | string | The slug of the wedding service page.               |
| content | string | HTML content representing the wedding service page. |

The `content` field holds HTML markup showcasing the details of the wedding service. It encompasses text descriptions, pricing tables, and structured content. This HTML structure may consist of various elements and classes used for styling and structuring the content.

<div style="height: 50px;"></div>

### Packages: `/custom/v1/packages`

The endpoint `/custom/v1/packages` returns JSON data providing information about service packages related to weddings. The response is an array of objects, each representing a distinct wedding package. Each object includes the following fields:

| Field    | Type   | Description                                             |
| -------- | ------ | ------------------------------------------------------- |
| pageId   | number | The ID of the page associated with the service package. |
| category | string | The category of the package.                            |
| price    | string | The price of the package.                               |
| features | array  | An array of features included in the package.           |
| url      | string | URL link to the associated service page.                |

Each package object contains details such as the package's category, price, features included, and a link to the respective service page.
