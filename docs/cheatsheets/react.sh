# -- React Complete Cheat Sheet

# -- -----------------------------
# -- 1. Setup and Basic Configuration
# -- -----------------------------

# Create a new React app using Create React App
npx create-react-app my-app

# Start the development server
npm start  # Starts the React app in development mode

# Build the app for production
npm run build  # Bundles the app into static files for production

# -- -----------------------------
# -- 2. Basic Component Structure
# -- -----------------------------

import React from 'react';

# Functional Component Example
const MyComponent = () => {
  return (
    <div>
      <h1>Hello, React!</h1>
    </div>
  );
};

export default MyComponent;

# -- -----------------------------
# -- 3. Handling State with useState
# -- -----------------------------

import React, { useState } from 'react';

const Counter = () => {
  const [count, setCount] = useState(0);  # useState to manage component state

  return (
    <div>
      <p>Count: {count}</p>
      <button onClick={() => setCount(count + 1)}>Increment</button>  # Update state on button click
    </div>
  );
};

export default Counter;

# -- -----------------------------
# -- 4. Handling Input with Forms and Controlled Components
# -- -----------------------------

import React, { useState } from 'react';

const FormComponent = () => {
  const [name, setName] = useState('');  # State to handle input value

  const handleSubmit = (e) => {
    e.preventDefault();
    alert(`Hello, ${name}`);  # Submit action
  };

  return (
    <form onSubmit={handleSubmit}>
      <input
        type="text"
        value={name}
        onChange={(e) => setName(e.target.value)}  # Update state as input changes
        placeholder="Enter your name"
      />
      <button type="submit">Submit</button>
    </form>
  );
};

export default FormComponent;

# -- -----------------------------
# -- 5. useEffect for Side Effects
# -- -----------------------------

import React, { useState, useEffect } from 'react';

const TimerComponent = () => {
  const [seconds, setSeconds] = useState(0);

  useEffect(() => {
    const interval = setInterval(() => {
      setSeconds((prev) => prev + 1);
    }, 1000);

    return () => clearInterval(interval);  # Cleanup on unmount
  }, []);  # Empty dependency array ensures the effect runs once

  return <div>Seconds: {seconds}</div>;
};

export default TimerComponent;

# -- -----------------------------
# -- 6. Conditional Rendering
# -- -----------------------------

import React, { useState } from 'react';

const ToggleComponent = () => {
  const [isVisible, setIsVisible] = useState(true);

  return (
    <div>
      {isVisible && <p>This text is visible!</p>}  # Conditionally render content
      <button onClick={() => setIsVisible(!isVisible)}>Toggle Visibility</button>
    </div>
  );
};

export default ToggleComponent;

# -- -----------------------------
# -- 7. Rendering Lists with map()
# -- -----------------------------

import React from 'react';

const ListComponent = () => {
  const items = ['Item 1', 'Item 2', 'Item 3'];

  return (
    <ul>
      {items.map((item, index) => (
        <li key={index}>{item}</li>  # Unique key for each item
      ))}
    </ul>
  );
};

export default ListComponent;

# -- -----------------------------
# -- 8. Handling Forms with useRef
# -- -----------------------------

import React, { useRef } from 'react';

const UncontrolledForm = () => {
  const inputRef = useRef(null);  # useRef to access DOM elements

  const handleSubmit = (e) => {
    e.preventDefault();
    alert(`Input value: ${inputRef.current.value}`);
  };

  return (
    <form onSubmit={handleSubmit}>
      <input ref={inputRef} type="text" placeholder="Enter something" />
      <button type="submit">Submit</button>
    </form>
  );
};

export default UncontrolledForm;

# -- -----------------------------
# -- 9. Prop Drilling (Passing Props to Child Components)
# -- -----------------------------

import React from 'react';

const ChildComponent = ({ message }) => {
  return <p>{message}</p>;
};

const ParentComponent = () => {
  const message = 'Hello from Parent';

  return <ChildComponent message={message} />;  # Passing props to child
};

export default ParentComponent;

# -- -----------------------------
# -- 10. useContext for Global State
# -- -----------------------------

import React, { useContext, useState } from 'react';

const MyContext = React.createContext();  # Create a context

const ParentComponent = () => {
  const [value, setValue] = useState('Context Value');

  return (
    <MyContext.Provider value={value}>
      <ChildComponent />
    </MyContext.Provider>
  );
};

const ChildComponent = () => {
  const value = useContext(MyContext);  # Access the context value

  return <p>{value}</p>;
};

export default ParentComponent;

# -- -----------------------------
# -- 11. useReducer for Complex State Management
# -- -----------------------------

import React, { useReducer } from 'react';

const initialState = { count: 0 };

function reducer(state, action) {
  switch (action.type) {
    case 'increment':
      return { count: state.count + 1 };
    case 'decrement':
      return { count: state.count - 1 };
    default:
      return state;
  }
}

const ReducerComponent = () => {
  const [state, dispatch] = useReducer(reducer, initialState);

  return (
    <div>
      <p>Count: {state.count}</p>
      <button onClick={() => dispatch({ type: 'increment' })}>Increment</button>
      <button onClick={() => dispatch({ type: 'decrement' })}>Decrement</button>
    </div>
  );
};

export default ReducerComponent;

# -- -----------------------------
# -- 12. Fetching Data from API (useEffect + fetch)
# -- -----------------------------

import React, { useEffect, useState } from 'react';

const DataFetchingComponent = () => {
  const [data, setData] = useState([]);

  useEffect(() => {
    fetch('https://jsonplaceholder.typicode.com/posts')
      .then((response) => response.json())
      .then((json) => setData(json))
      .catch((error) => console.error('Error fetching data:', error));
  }, []);  # Runs once on mount

  return (
    <div>
      {data.map((item) => (
        <p key={item.id}>{item.title}</p>
      ))}
    </div>
  );
};

export default DataFetchingComponent;

# -- -----------------------------
# -- 13. React Router for Navigation
# -- -----------------------------

# Install React Router
npm install react-router-dom

import React from 'react';
import { BrowserRouter as Router, Route, Switch, Link } from 'react-router-dom';

const Home = () => <h2>Home</h2>;
const About = () => <h2>About</h2>;

const App = () => {
  return (
    <Router>
      <nav>
        <Link to="/">Home</Link> | <Link to="/about">About</Link>
      </nav>
      <Switch>
        <Route path="/" exact component={Home} />
        <Route path="/about" component={About} />
      </Switch>
    </Router>
  );
};

export default App;

# -- -----------------------------
# -- 14. Handling Events in React
# -- -----------------------------

import React from 'react';

const EventHandlingComponent = () => {
  const handleClick = () => {
    alert('Button Clicked!');
  };

  return (
    <div>
      <button onClick={handleClick}>Click Me</button>
    </div>
  );
};

export default EventHandlingComponent;

# -- -----------------------------
# -- 15. Styling Components (CSS)
# -- -----------------------------

import React from 'react';
import './App.css';  # Importing a CSS file

const StyledComponent = () => {
  return (
    <div className="container">
      <h1 className="title">Styled Component</h1>
    </div>
  );
};

export default StyledComponent;

# App.css
.container {
  text-align: center;
  background-color: #f0f0f0;
  padding: 20px;
}

.title {
  color: #333;
}

# -- -----------------------------
# -- 16. Useful Snippets
# -- -----------------------------

# Ternary Conditional Rendering
const isLoggedIn = true;
return <div>{isLoggedIn ? <p>Welcome back!</p> : <p>Please sign in</p>}</div>;

# Inline CSS Styling
const MyStyledComponent = () => {
  return (
    <div style={{ color: 'blue', fontSize: '20px' }}>
      This is a styled component with inline styles
    </div>
  );
};

export default MyStyledComponent;

# Fragment (<> </>) for Wrapping Components Without Extra Divs
return (
  <>
    <h1>Title</h1>
    <p>Description text</p>
  </>
);
