import React from 'react';
import { StyleSheet, View } from "react-native";
import { Color } from "@/globals/Styles";

const Line = ({ color = Color.colorWhitesmoke_200 }) => {
    return (
        <View style={[styles.line, { backgroundColor: color }]} />
    );
};

const styles = StyleSheet.create({
    line: {
        height: 1,
        width: '100%',
		position: 'relative',
		marginHorizontal: 20,
		alignSelf: 'center',
		
		
    }
});

export default Line;
