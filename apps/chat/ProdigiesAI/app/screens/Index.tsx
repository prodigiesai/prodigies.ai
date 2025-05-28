import React, { useState, useEffect, useRef, useCallback } from 'react';
import { ScrollView, View, Text, Image, StyleSheet, TouchableOpacity, Animated, Pressable, FlatList } from 'react-native';
import useTranslation from '@/hooks/useTranslation';
import { useNavigation, useRouter } from 'expo-router';
import { FontFamily, FontSize, Color, Padding, Border } from "@/globals/Styles";
import { Products } from '@/services/Products';
import NavigationBar from '@/components/NavigationBar';
import { AntDesign } from '@expo/vector-icons';
import { Item, Category } from '@/interfaces/CategoryProps';
import { getRandomText, getRandomDate } from '@/utils/helpers';
import FlatListMenu from '@/components/FlatListMenu';
import Line from '@/components/line';


const Index: React.FC = () => {

    const router = useRouter();
    const navigation = useNavigation();
    const opacity = useRef(new Animated.Value(0)).current;
    const [activeButton, setActiveButton] = useState<'aiAssistants' | 'chats'>('chats');
    const allItems = React.useMemo(() => Products.reduce<Item[]>((accumulator, category) => accumulator.concat(category.items), []), []);
    const [selectedCategory, setSelectedCategory] = useState<string>('All');
    const { translate, isReady } = useTranslation();
    // Adjusted for correct property based on provided interfaces
    const filtered = React.useMemo(() => Products.filter((category: Category) => selectedCategory === 'All' || category.category === selectedCategory), [selectedCategory]);



    useEffect(() => {
        Animated.timing(opacity, {
            toValue: 1,
            duration: 100,
            useNativeDriver: true,
        }).start();
    }, []); // Empty dependency array means this runs on mount

    useEffect(() => {
        navigation.setOptions({
            headerShown: true,
            title: translate("Assistants"),
            headerBackVisible: false,
            headerRight: renderHeaderRight,
        });
    }, [navigation, isReady, translate]);


    const switchContent = useCallback((activeButton: 'aiAssistants' | 'chats') => {
        Animated.timing(opacity, {
            toValue: 0,
            duration: 100,
            useNativeDriver: true,
        }).start(() => {
            setActiveButton(activeButton);
            Animated.timing(opacity, {
                toValue: 1,
                duration: 100,
                useNativeDriver: true,
            }).start();
        });
    }, [opacity]);

    const renderHeaderRight = useCallback(() => (
        <>
            <TouchableOpacity style={styles.settingsButton} onPress={() => router.push('../screens/Menu')}>
                <AntDesign name="search1" size={24} color={Color.greyscale900} />
            </TouchableOpacity>
            <TouchableOpacity style={styles.settingsButton} onPress={() => router.push('../screens/Menu')}>
                <AntDesign name="setting" size={24} color={Color.greyscale900} />
            </TouchableOpacity>
        </>
    ), [router]);

    const renderItem = useCallback(({ item }: { item: Item }) => (
        <>
        <Pressable style={styles.itemContainer} onPress={() => switchContent('chats')}>
            <Image style={styles.imageIcon} resizeMode="cover" source={item.image} />
            <View style={styles.itemContent}>
                <View style={styles.messages} >
                    <Text style={styles.itemTitle}>{item.name}</Text>
                    <Text style={styles.itemSubtitle}>   {item.description}  </Text>
                </View>
                <Text numberOfLines={2} ellipsizeMode="tail" style={styles.itemDescription}>{getRandomText()}</Text>
                <Text style={styles.itemDate}>{getRandomDate()}</Text>
            </View>
            <AntDesign name="right" size={18} color={Color.greyscale500} />
           
        </Pressable>
         <Line color = {Color.colorWhitesmoke_200 }/>
         </>
    ), []);

    return (
        <>
            {/* <LinearGradient
                colors={['#c7cffd', '#fcdee7']}
                style={{ flex: 1 }}
            > */}
                <Animated.View style={[styles.container, { opacity }]}>
                    {activeButton === 'chats' ? (
                        <FlatList<Item>
                            data={allItems}
                            renderItem={renderItem}
                            keyExtractor={(item, index) => `item-${index}`}
                        />
                    ) : (
                        <ScrollView style={styles.container}>
                            <FlatListMenu categories={Products} selected={setSelectedCategory} />
                            {filtered.map((category, index) => (
                                <View key={index.toString()} style={styles.categoryContainer}>
                                    <Text style={styles.headerTitle}>{category.category}</Text>
                                    <ScrollView horizontal showsHorizontalScrollIndicator={false}>
                                        {category.items.map((product, productIndex) => (
                                            <TouchableOpacity key={productIndex.toString()} style={styles.productButton} onPress={() => switchContent('chats')}>
                                                <View style={styles.card}>
                                                    <Image style={styles.productImage} source={product.image} />
                                                    <View>
                                                        <Text numberOfLines={2} ellipsizeMode="tail" style={styles.productName}>{product.name}</Text>
                                                        <Text numberOfLines={2} ellipsizeMode="tail" style={styles.productDescription}>{product.description}</Text>
                                                    </View>
                                                </View>
                                            </TouchableOpacity>
                                        ))}
                                    </ScrollView>
                                </View>
                            ))}
                        </ScrollView>
                    )}
                </Animated.View>
            {/* </LinearGradient> */}
            <NavigationBar selected={(newView: 'aiAssistants' | 'chats') => switchContent(newView)} screen={activeButton} />
        </>
    );
};

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: Color.white,
    },
    categoryContainer: {
        paddingTop: Padding.p_5xs,
        paddingBottom: Padding.p_5xs,
        // marginVertical: 6,
        marginHorizontal: 12,

    },
    headerTitle: {
        fontSize: 18,
        fontFamily: FontFamily.bodyXlarge,
    },
    productButton: {
        marginRight: Padding.p_base,
        marginTop: Padding.p_3xs,
        padding: Padding.p_5xs,
        paddingTop: Padding.p_5xs,
        // alignItems: "center",
        width: 175,
        backgroundColor: Color.colorWhitesmoke_200,
        borderRadius: 14,
        // shadowColor:Color.greyscale500,
        // shadowOpacity:0.4,
        // shadowRadius:1,
        // shadowOffset: {
        //     width: 0,
        //     height: 7,
        // },
        // elevation: 5, // Adjust elevation to control shadow depth on Android
        borderWidth:1,
        borderColor:Color.greyscale500,
    },

    itemContainer: {
        flexDirection: "row",
        alignItems: "center",
        // borderRadius: 14,
        backgroundColor: Color.white,//backgroundColor: 'rgba(128, 0, 128, 0.1)',
        padding: 5,
        // marginVertical: 3,
        paddingHorizontal:10,
        // marginHorizontal: 18,
        // borderBottomWidth:0.5,
        // borderBottomColor: Color.greyscale500
        // shadowColor:Color.greyscale500,
        // shadowOpacity:0.4,
        // shadowRadius:1,
        // shadowOffset: {
        //     width: 0,
        //     height: 2,
        // },
    },
    productImage: {
        width: 56,
        height: 56,
        borderRadius: Border.br_81xl,
        marginRight: 10,
    },
    productName: {
        fontSize: 14,
        fontFamily: FontFamily.bodyLargeSemibold,
        paddingTop: Padding.p_3xs,
    },
    productDescription: {
        fontSize: FontSize.bodyXsmall_size,
        paddingBottom: Padding.p_8xs,
        fontFamily: FontFamily.bodyMedium_size,
    },

    itemSubtitle: {
        fontSize: FontSize.bodySmall_size,
        fontFamily: FontFamily.bodyMedium_size,
        alignSelf: "center",
        textAlign: "right",
        color: Color.greyscale700,
        flex: 1,
    },
    settingsButton: {
        marginRight: Padding.p_8xs,
        marginLeft: Padding.p_5xs,
        width: 28,
        height: 28,
    },

    imageIcon: {
        width: 43,
        height: 40,
        borderRadius: 100,
    },



    messages: {
        flexDirection: "row",
    },

    itemContent: {
        flex: 1,
        marginLeft: 10,
        paddingRight: 10,
    },
    itemTitle: {
        fontSize: FontSize.bodyLarge,
        fontFamily: FontFamily.bodyXlarge,
        color: Color.greyscale900,
    },
    itemDescription: {
        fontSize: FontSize.bodyMedium_size,
        fontFamily: FontFamily.bodyMedium_size,
        color: Color.greyscale700,
        paddingTop: Padding.p_8xs,
    },
    itemDate: {
        fontSize: 10,
        fontFamily: FontFamily.bodyMedium_size,
        color: Color.greyscale700,
        // marginTop: 6,
        paddingTop: Padding.p_8xs,
    },
    arrowIcon: {
        width: 24,
        height: 24,
    },
    card: {
        flexDirection: "row",
    },


});

export default Index;
