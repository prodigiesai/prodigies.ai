import { ImageSourcePropType } from 'react-native';

export interface Item {
  name: string;
  description: string;
  image: ImageSourcePropType; // Use 'any' for dynamic require() paths, or specify a more precise type if possible
  onPressPath: string;
}

export interface Category {
  category: string;
  items: Item[];
}