# MongoDB Cheat Sheet

# -----------------------------
# 1. MongoDB Shell Basics
# -----------------------------

mongo  # Open MongoDB shell
show dbs  # Show all databases
use <database_name>  # Use or switch to a database
show collections  # Show all collections in the current database

# -----------------------------
# 2. Database Operations
# -----------------------------

use <database_name>  # Create or switch to a database (created implicitly)
db.dropDatabase()  # Drop the current database
db  # Show the current database

# -----------------------------
# 3. Collection Operations
# -----------------------------

db.createCollection('<collection_name>')  # Create a new collection
db.<collection_name>.drop()  # Drop a collection
db.<collection_name>.renameCollection('<new_collection_name>')  # Rename a collection

# -----------------------------
# 4. Insert Documents
# -----------------------------

db.<collection_name>.insertOne({ "name": "John Doe", "age": 30, "email": "john@example.com" })  # Insert one document
db.<collection_name>.insertMany([  # Insert multiple documents
    { "name": "Alice", "age": 25, "email": "alice@example.com" },
    { "name": "Bob", "age": 35, "email": "bob@example.com" }
])

# -----------------------------
# 5. Query Documents
# -----------------------------

db.<collection_name>.findOne({ "name": "John Doe" })  # Find one document by filter
db.<collection_name>.find()  # Find all documents in a collection
db.<collection_name>.find({ "age": { "$gt": 25 } })  # Find documents where age is greater than 25
db.<collection_name>.find({}, { "name": 1, "age": 1, "_id": 0 })  # Projection: show only name and age, hide _id

# -----------------------------
# 6. Update Documents
# -----------------------------

db.<collection_name>.updateOne({ "name": "John Doe" }, { "$set": { "age": 31 } })  # Update one document
db.<collection_name>.updateMany({ "age": { "$gt": 30 } }, { "$set": { "status": "active" } })  # Update multiple documents
db.<collection_name>.replaceOne({ "name": "John Doe" }, { "name": "John Doe", "age": 32, "email": "john.new@example.com" })  # Replace a document

# -----------------------------
# 7. Delete Documents
# -----------------------------

db.<collection_name>.deleteOne({ "name": "John Doe" })  # Delete one document
db.<collection_name>.deleteMany({ "age": { "$lt": 30 } })  # Delete multiple documents
db.<collection_name>.deleteMany({})  # Delete all documents in a collection

# -----------------------------
# 8. Indexes
# -----------------------------

db.<collection_name>.createIndex({ "name": 1 })  # Create an ascending index on "name" field
db.<collection_name>.createIndex({ "email": 1 }, { "unique": true })  # Create a unique index on "email" field
db.<collection_name>.getIndexes()  # List all indexes on a collection
db.<collection_name>.dropIndex('<index_name>')  # Drop an index by name

# -----------------------------
# 9. Aggregation
# -----------------------------

db.<collection_name>.aggregate([  # Simple aggregation: group by "age" and count documents
    { "$group": { "_id": "$age", "count": { "$sum": 1 } } }
])

db.<collection_name>.aggregate([  # Multiple stages: match, group, sort
    { "$match": { "age": { "$gt": 25 } } },  # Match age > 25
    { "$group": { "_id": "$age", "total": { "$sum": 1 } } },  # Group by age
    { "$sort": { "_id": 1 } }  # Sort by age in ascending order
])

# -----------------------------
# 10. Working with Relationships
# -----------------------------

db.users.insertOne({  # Insert a reference to another document (Manual Referencing)
    "name": "John",
    "email": "john@example.com",
    "profile_id": ObjectId("605c72c5f5b71b008c9a5c56")  # Reference to a profile document
})

db.users.aggregate([  # Perform a lookup (similar to SQL JOIN)
    {
        "$lookup": {
            "from": "profiles",  # Target collection
            "localField": "profile_id",  # Local field (in users)
            "foreignField": "_id",  # Foreign field (in profiles)
            "as": "profile_info"  # New array field in the output
        }
    }
])

# -----------------------------
# 11. Export and Import Data
# -----------------------------

mongoexport --db=<database_name> --collection=<collection_name> --out=<filename.json>  # Export collection to JSON
mongoimport --db=<database_name> --collection=<collection_name> --file=<filename.json> --jsonArray  # Import data from JSON

# -----------------------------
# 12. Backup and Restore MongoDB
# -----------------------------

mongodump --db=<database_name> --out=<backup_directory>  # Backup a MongoDB database
mongorestore --db=<database_name> <backup_directory>/<database_name>  # Restore a MongoDB database from backup

# -----------------------------
# 13. User and Role Management
# -----------------------------

db.createUser({  # Create a new user with roles
    user: "adminUser",
    pwd: "password123",
    roles: [{ role: "dbAdmin", db: "admin" }, { role: "readWrite", db: "exampleDb" }]
})

db.grantRolesToUser("adminUser", [{ role: "read", db: "anotherDb" }])  # Grant additional roles to an existing user

# -----------------------------
# 14. Miscellaneous Commands
# -----------------------------

db.serverStatus()  # Get MongoDB server status
db.currentOp()  # Get the current operations running in MongoDB
db.<collection_name>.dropIndexes()  # Drop all indexes on a collection
db.<collection_name>.find({ "age": { "$gt": 25 } }).explain()  # Get query plan

# -----------------------------
# 15. Exit MongoDB Shell
# -----------------------------

exit  # Exit the MongoDB shell
