### User Management

---

#### Create User

##### Description:
- An endpoint to register a new user.

##### Method:
- `POST`

##### Endpoint:
- (Specify the endpoint here, e.g., `/api/create-user`)

##### Request Body Parameters:

- **email** (string, required): The email of the user.
  - Example: `john@example.com`
- **password** (string, required): The password for the user.
  - Example: `secret123`

##### Responses:

- `201 Created`:
  - **Content-Type**: `application/json`
  - **Body**:
    ```json
    {
      "message": "User created successfully"
    }
    ```

- `500 Internal Server Error`:
  - **Content-Type**: `application/json`
  - **Body**:
    ```json
    {
      "message": "User creation failed"
    }
    ```

- `422 Unprocessable Entity`:
  - **Content-Type**: `application/json`
  - **Body**:
    ```json
    {
      "message": "Validation failed",
      "errors": {
        "email": ["The email field is required."]
        // Other validation errors can be added as they are defined in `$this->getValidationRules()`
      }
    }
    ```

##### Implementation Notes:

- The request body undergoes validation based on rules retrieved from `$this->getValidationRules()`.
- If the validation fails, detailed error messages will be returned.
- The user's email and password are extracted from the request and used for user creation.
- If the user creation is successful, a `201 Created` response is returned.
- If the user creation fails, a `500 Internal Server Error` response is returned.

