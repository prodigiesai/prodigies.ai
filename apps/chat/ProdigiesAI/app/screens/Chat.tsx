import React from 'react';
import { Text, StyleSheet } from 'react-native';
import { FontFamily, Padding } from "@/globals/Styles"; // Ensure these are defined

const Chat: React.FC = () => {
  return (
    <Text numberOfLines={2} ellipsizeMode="tail" style={styles.productName}>
      Hello World
    </Text>
  );
};

const styles = StyleSheet.create({
  productName: {
    fontSize: 14,
    fontFamily: FontFamily.bodyLargeSemibold,
    paddingTop: Padding.p_3xs,
  },
});

export default Chat;
