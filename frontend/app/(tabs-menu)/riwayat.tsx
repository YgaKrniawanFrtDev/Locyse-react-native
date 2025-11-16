import { View, Text, StyleSheet } from 'react-native'
import React from 'react'

export default function riwayat() {
  return (
    <View style={styles.container}>
      <Text style={styles.text}>Riwayat</Text>
    </View>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    justifyContent: 'center',
    alignItems: 'center',
    backgroundColor: '#F5F6FA',
  },
  text: {
    fontSize: 18,
    color: '#2C3E50',
  },
})
