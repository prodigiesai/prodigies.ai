# Installing Node.js and npm

brew install node  # Install Node.js and npm via Homebrew
node -v  # Check Node.js version
npm -v  # Check npm version

# Working with Node.js

node <file.js>  # Run a JavaScript file using Node.js
node  # Open a Node.js REPL (Read-Eval-Print Loop)
node --inspect <file.js>  # Debug a JavaScript file with the Node.js debugger
node --version  # Check the version of Node.js

# npm (Node Package Manager) Commands

npm init  # Initialize a new Node.js project (creates package.json)
npm init -y  # Initialize a project with default settings
npm install <package>  # Install a package and add it to dependencies in package.json
npm install -g <package>  # Install a package globally
npm uninstall <package>  # Uninstall a package and remove it from package.json
npm install  # Install all dependencies listed in package.json
npm update  # Update all packages to the latest version
npm outdated  # List outdated packages
npm list  # List installed packages in the current project
npm run <script>  # Run a custom script defined in package.json
npm test  # Run tests defined in package.json

# Package Management

npm install --save <package>  # Install and add a package to dependencies in package.json
npm install --save-dev <package>  # Install and add a package to devDependencies in package.json
npm uninstall --save <package>  # Uninstall and remove a package from dependencies
npm uninstall --save-dev <package>  # Uninstall and remove a package from devDependencies

# npx (Node Package Runner)

npx <command>  # Run a package without installing it globally
npx create-react-app <app-name>  # Example: Create a React.js project without globally installing create-react-app

# Node.js Package Management

npm cache clean --force  # Clear the npm cache
npm rebuild  # Rebuild the project's dependencies
npm ci  # Clean install of dependencies (removes node_modules and installs clean versions)

# npm Audit Commands

npm audit  # Perform a security audit of the dependencies
npm audit fix  # Automatically install updates to fix vulnerabilities
npm audit fix --force  # Apply potentially breaking changes to fix vulnerabilities

# Node.js Debugging

node inspect <file.js>  # Start the Node.js debugger
node --inspect-brk <file.js>  # Start the debugger and pause at the first line of the script

# Node.js Environment

NODE_ENV=production node <file.js>  # Set the Node.js environment to production and run the script
NODE_ENV=development node <file.js>  # Set the Node.js environment to development and run the script

# Global npm Commands

npm -g list  # List globally installed npm packages
npm -g uninstall <package>  # Uninstall a globally installed package
npm -g outdated  # List outdated global packages
npm -g update <package>  # Update a globally installed package
npm -g install <package>  # Install a package globally

# Node.js Version Manager (nvm)

nvm install <version>  # Install a specific version of Node.js
nvm use <version>  # Switch to a specific version of Node.js
nvm ls  # List all installed versions of Node.js
nvm alias default <version>  # Set the default version of Node.js to use

# Useful npm Packages

npm install express  # Install Express.js web framework
npm install nodemon --save-dev  # Install Nodemon for auto-restarting Node.js on file changes
npm install mongoose  # Install Mongoose for MongoDB database management
npm install axios  # Install Axios for making HTTP requests

# Running Node.js Scripts with npm

npm start  # Run the start script from package.json
npm run dev  # Run the dev script from package.json (often used with Nodemon)
npm run build  # Run the build script from package.json (commonly used in front-end projects)

# Linting and Formatting

npm install eslint --save-dev  # Install ESLint for code linting
npm install prettier --save-dev  # Install Prettier for code formatting
npx eslint .  # Run ESLint on the current directory
npx prettier --write .  # Format code using Prettier

# Testing in Node.js

npm install jest --save-dev  # Install Jest for testing
npm test  # Run tests using npm's test script (typically configured to run Jest)

# Node.js Server Example

touch app.js  # Create a new file for a simple Express.js app
echo "const express = require('express'); const app = express(); app.get('/', (req, res) => res.send('Hello World!')); app.listen(3000, () => console.log('Server running on port 3000'));" > app.js  # Add a basic Express server
node app.js  # Run the Node.js server
