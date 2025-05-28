# -- React Native Complete Cheat Sheet

# -- -----------------------------
# -- 1. Setup and Basic Configuration
# -- -----------------------------

# Install React Native CLI globally
npm install -g react-native-cli

# Create a new React Native project
npx react-native init MyApp  # Creates a new project with the default template

# Running the app (iOS or Android)
npx react-native run-ios  # For iOS
npx react-native run-android  # For Android

# -- -----------------------------
# -- 2. Basic Component Structure
# -- -----------------------------

import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

const MyComponent = () => {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Hello, React Native!</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#fff',
  },
  text: {
    fontSize: 20,
    color: '#333',
  },
});

export default MyComponent;

# -- -----------------------------
# -- 3. Handling State with useState
# -- -----------------------------

import React, { useState } from 'react';
import { View, Button, Text } from 'react-native';

const Counter = () => {
  const [count, setCount] = useState(0);  # Initial state with useState

  return (
    <View>
      <Text>Count: {count}</Text>
      <Button title="Increment" onPress={() => setCount(count + 1)} />  # Updating state
    </View>
  );
};

export default Counter;

# -- -----------------------------
# -- 4. Handling Input with TextInput
# -- -----------------------------

import React, { useState } from 'react';
import { View, TextInput, Text, StyleSheet } from 'react-native';

const InputComponent = () => {
  const [text, setText] = useState('');  # State to hold input text

  return (
    <View style={styles.container}>
      <TextInput
        style={styles.input}
        placeholder="Type something..."
        value={text}
        onChangeText={(newText) => setText(newText)}  # Handling input change
      />
      <Text>{text}</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
  },
  input: {
    height: 40,
    borderColor: 'gray',
    borderWidth: 1,
    marginBottom: 10,
    padding: 10,
  },
});

export default InputComponent;

# -- -----------------------------
# -- 5. Styling Components
# -- -----------------------------

import React from 'react';
import { View, Text, StyleSheet } from 'react-native';

const MyStyledComponent = () => {
  return (
    <View style={styles.container}>
      <Text style={styles.title}>This is a title</Text>
      <Text style={styles.subtitle}>This is a subtitle</Text>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
    backgroundColor: '#f5f5f5',
  },
  title: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#000',
  },
  subtitle: {
    fontSize: 18,
    color: '#666',
  },
});

export default MyStyledComponent;

# -- -----------------------------
# -- 6. FlatList for Rendering Lists
# -- -----------------------------

import React from 'react';
import { FlatList, Text, View, StyleSheet } from 'react-native';

const MyListComponent = () => {
  const data = [
    { id: '1', name: 'Item 1' },
    { id: '2', name: 'Item 2' },
    { id: '3', name: 'Item 3' },
  ];

  return (
    <FlatList
      data={data}
      keyExtractor={(item) => item.id}
      renderItem={({ item }) => (
        <View style={styles.item}>
          <Text>{item.name}</Text>
        </View>
      )}
    />
  );
};

const styles = StyleSheet.create({
  item: {
    padding: 20,
    borderBottomWidth: 1,
    borderColor: '#ccc',
  },
});

export default MyListComponent;

# -- -----------------------------
# -- 7. ScrollView for Scrollable Content
# -- -----------------------------

import React from 'react';
import { ScrollView, Text, StyleSheet } from 'react-native';

const MyScrollView = () => {
  return (
    <ScrollView style={styles.container}>
      <Text style={styles.text}>Item 1</Text>
      <Text style={styles.text}>Item 2</Text>
      <Text style={styles.text}>Item 3</Text>
      {/* Add as many items as needed */}
    </ScrollView>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
  },
  text: {
    fontSize: 18,
    marginVertical: 10,
  },
});

export default MyScrollView;

# -- -----------------------------
# -- 8. Navigation (React Navigation)
# -- -----------------------------

# Install React Navigation
npm install @react-navigation/native
npm install @react-navigation/stack  # Stack navigation

# Install required dependencies
expo install react-native-screens react-native-safe-area-context

# Navigation Setup
import React from 'react';
import { NavigationContainer } from '@react-navigation/native';
import { createStackNavigator } from '@react-navigation/stack';
import HomeScreen from './HomeScreen';
import DetailsScreen from './DetailsScreen';

const Stack = createStackNavigator();

const App = () => {
  return (
    <NavigationContainer>
      <Stack.Navigator initialRouteName="Home">
        <Stack.Screen name="Home" component={HomeScreen} />
        <Stack.Screen name="Details" component={DetailsScreen} />
      </Stack.Navigator>
    </NavigationContainer>
  );
};

export default App;

# -- -----------------------------
# -- 9. Fetching Data (API Requests)
# -- -----------------------------

import React, { useState, useEffect } from 'react';
import { View, Text, StyleSheet } from 'react-native';

const FetchDataComponent = () => {
  const [data, setData] = useState([]);

  useEffect(() => {
    fetch('https://jsonplaceholder.typicode.com/posts')  # Sample API call
      .then((response) => response.json())
      .then((json) => setData(json))
      .catch((error) => console.error(error));
  }, []);

  return (
    <View style={styles.container}>
      {data.map((item) => (
        <Text key={item.id}>{item.title}</Text>
      ))}
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    padding: 20,
  },
});

export default FetchDataComponent;

# -- -----------------------------
# -- 10. Modal Component
# -- -----------------------------

import React, { useState } from 'react';
import { Modal, View, Text, Button, StyleSheet } from 'react-native';

const ModalComponent = () => {
  const [modalVisible, setModalVisible] = useState(false);

  return (
    <View style={styles.container}>
      <Button title="Show Modal" onPress={() => setModalVisible(true)} />
      <Modal
        transparent={true}
        visible={modalVisible}
        onRequestClose={() => setModalVisible(false)}
      >
        <View style={styles.modalView}>
          <Text>This is a modal!</Text>
          <Button title="Close Modal" onPress={() => setModalVisible(false)} />
        </View>
      </Modal>
    </View>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
  },
  modalView: {
    margin: 20,
    backgroundColor: 'white',
    padding: 35,
    alignItems: 'center',
    shadowColor: '#000',
    shadowOffset: {
      width: 0,
      height: 2,
    },
    shadowOpacity: 0.25,
    shadowRadius: 4,
    elevation: 5,
  },
});

export default ModalComponent;

# -- -----------------------------
# -- 11. Useful Snippets
# -- -----------------------------

# Conditional Rendering
const MyComponent = () => {
  const [isVisible, setIsVisible] = useState(true);

  return (
    <View>
      {isVisible && <Text>Visible Text</Text>}  # Conditional rendering
      <Button title="Toggle Visibility" onPress={() => setIsVisible(!isVisible)} />
    </View>
  );
};

# Platform-Specific Code
import { Platform } from 'react-native';

const PlatformSpecificComponent = () => (
  <Text>{Platform.OS === 'ios' ? 'Running on iOS' : 'Running on Android'}</Text>
);

# Image Component
import { Image } from 'react-native';

const MyImageComponent = () => (
  <Image
    source={{ uri: 'https://example.com/image.jpg' }}
    style={{ width: 100, height: 100 }}
  />
);
