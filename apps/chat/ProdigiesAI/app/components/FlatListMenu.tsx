import React, { useState } from 'react';
import { StyleSheet, View, Text, TouchableOpacity, FlatList } from "react-native";
import { Padding, Border, FontSize, Color, FontFamily } from "@/globals/Styles";
import { Category } from '@/interfaces/CategoryProps';
// Assuming your categories prop will be an array of ItemCategory objects
interface Props {
    categories: Category[]; // Corrected type based on your provided structure
    selected: (category: string) => void; // Corrected callback type
}

const FlatListMenu: React.FC<Props> = ({ categories, selected }) => {

    const [selectedCategory, setSelectedCategory] = useState('All');

    // Correcting the mapping to extract category names
    const categoryNames = ['All', ...categories.map(category => category.category)];

    return (
        <View style={styles.container}>
            <FlatList
                data={categoryNames}
                horizontal
                showsHorizontalScrollIndicator={false}
                renderItem={({ item }) => (
                    <TouchableOpacity
                        style={[styles.categoryButton, selectedCategory === item && styles.selectedCategory]}
                        onPress={() => {
                            setSelectedCategory(item);
                            selected(item); // Correctly invoking the passed callback
                        }}
                    >
                        <Text style={[styles.categoryText, selectedCategory === item && styles.selectedCategoryText]}>{item}</Text>
                    </TouchableOpacity>
                )}
                keyExtractor={(item, index) => index.toString()}
                style={styles.categoriesList}
            />
        </View>
    );
};
const styles = StyleSheet.create({

    container: {

        alignSelf: "center",

    },
    selectedCategory: {
        backgroundColor: Color.primaryColor,
    },
    selectedCategoryText: {
        color: Color.white,
    },
    categoryButton: {
        paddingVertical: Padding.p_5xs,
        paddingHorizontal: Padding.p_base,
        justifyContent: "center",
        alignItems: "center",
        borderRadius: Border.br_81xl,
        borderColor: Color.lavender,
        borderWidth: 2,
        marginLeft: Padding.p_8xs,
        minWidth:90,
    },
    categoryText: {
        color: Color.primaryDark,
        fontSize: FontSize.bodyLarge,
        fontFamily: FontFamily.bodyLargeSemibold,
    },
    categoriesList: {
        backgroundColor: Color.white,
        maxHeight: 70,
        paddingTop: Padding.p_5xs,
        paddingBottom: Padding.p_5xs,
    },
});

export default FlatListMenu;