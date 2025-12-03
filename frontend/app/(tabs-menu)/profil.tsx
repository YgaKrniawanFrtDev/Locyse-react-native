import { View, Text, StyleSheet, ScrollView, TouchableOpacity } from 'react-native';
import { LinearGradient } from 'expo-linear-gradient';
import { Ionicons } from '@expo/vector-icons';

export default function profil() {
  return (
    <ScrollView style={styles.container}>
      <LinearGradient
        colors={['#7C3AED', '#4F46E5']}
        start={{ x: 0, y: 0 }}
        end={{ x: 1, y: 1 }}
        style={styles.header}>
        <View style={styles.headerContent}>
          <View>
            <View style={styles.profile}>
              <View>
                <Text style={styles.username}>Profile</Text>
              </View>
              <TouchableOpacity style={styles.iconProfile}>
                <View style={styles.iconContainer}>
                  <Ionicons name="settings-outline" size={30} color="#000000ff" />
                </View>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </LinearGradient>
      <View style={styles.section}>
      <Text style={styles.sectionTitle}>Biodata</Text>
        <View style={styles.activityCard}>
          <View style={styles.activityIcon}>
            <Ionicons name="person" size={20} color="#6C63FF" />
          </View>
          <View style={styles.activityContent}>
            <Text style={styles.activityTitle}>Username</Text>
            <Text style={styles.activityTime}>Ubah Username</Text>
          </View>
        </View>
        <View style={styles.activityCard}>
          <View style={styles.activityIcon}>
            <Ionicons name="key" size={20} color="#6C63FF" />
          </View>
          <View style={styles.activityContent}>
            <Text style={styles.activityTitle}>Password</Text>
            <Text style={styles.activityTime}>Ubah Password</Text>
          </View>
        </View>
      </View>
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Riwayat Absensi</Text>
        <View style={styles.activityCard}>
          <View style={styles.activityIcon}>
            <Ionicons name="checkmark-circle-outline" size={20} color="#24d00aff" />
          </View>
          <View style={styles.activityContent}>
            <Text style={styles.activityTitle}>Kamu Sudah Absen</Text>
            <Text style={styles.activityTime}>2 jam yang lalu</Text>
          </View>
        </View>
      </View>
      <View style={styles.section}>
        <Text style={styles.sectionTitle}>Lainya</Text>
        <View style={styles.activityCard}>
          <View style={styles.activityIcon}>
            <Ionicons name="log-out-outline" size={20} color="#ff0000ff" />
          </View>
          <View style={styles.activityContent}>
            <Text style={styles.activityTitle}>Logout</Text>
          </View>
        </View>
      </View>
    </ScrollView>
  )
}

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: '#F9FAFB',
    height: 500,
  },
  header: {
    paddingTop: 50,
    height: 130,
    paddingBottom: 30,
    paddingHorizontal: 20,
    borderBottomLeftRadius: 20,
    borderBottomRightRadius: 20,
  },
  headerContent: {
    flexDirection: 'row',
    justifyContent: 'space-between',
    alignItems: 'center',
    gap: 10,
  },
  profile: {
    flexDirection: 'row',
    width: '100%',
    justifyContent: 'space-between',
    alignItems: 'center'
  },
  iconProfile: {
    backgroundColor: 'white',
    height: 50,
    width: 50,
    borderRadius: 50,
    elevation: 2,
  },
  greeting: {
    fontSize: 16,
    color: '#FFF',
    opacity: 0.9,
  },
  iconContainer: {
  width: '100%',
  height: '100%',
  borderRadius: 16, 
  justifyContent: 'center',
  alignItems: 'center',
},
  username: {
    fontSize: 24,
    fontWeight: 'bold',
    color: '#FFF',
    marginTop: 4,
  },
  notificationBtn: {
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    padding: 10,
    borderRadius: 12,
  },
  statsContainer: {
    flexDirection: 'row',
    paddingHorizontal: 20,
    marginTop: -30,
    gap: 15,
  },
  buttonAct: {
    flexDirection: 'row',
    gap: 5,
  },
  buttonActStyle: {
    marginTop: 15,
    backgroundColor: '#FFFFFF',
    borderColor: 'E5E7EB',
    width: 128,
    height: 35,
    alignItems: 'center',
    alignContent: 'center',
    padding: 8,
    borderRadius: 5,
  },
  textActButton: {

  },
  statCard: {
    flex: 1,
    borderRadius: 20,
    overflow: 'hidden',
    elevation: 5,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 2 },
    shadowOpacity: 0.25,
    shadowRadius: 3.84,
  },
  statGradient: {
    padding: 20,
    alignItems: 'center',
  },
  statNumber: {
    fontSize: 32,
    fontWeight: 'bold',
    color: '#FFF',
    marginTop: 8,
  },
  statLabel: {
    fontSize: 14,
    color: '#FFF',
    marginTop: 4,
    opacity: 0.9,
  },
  section: {
    paddingHorizontal: 20,
    marginTop: 30,
  },
  sectionTitle: {
    fontSize: 20,
    fontWeight: 'bold',
    color: '#2C3E50',
    marginBottom: 15,
  },
  actionsGrid: {
    flexDirection: 'row',
    flexWrap: 'wrap',
    gap: 15,
  },
  actionCard: {
    width: '47%',
    backgroundColor: '#FFF',
    padding: 20,
    borderRadius: 15,
    alignItems: 'center',
    elevation: 1,
    shadowColor: '#0000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.2,
    shadowRadius: 1.20,
  },
  actionIcon: {
    width: 56,
    height: 56,
    borderRadius: 16,
    justifyContent: 'center',
    alignItems: 'center',
    marginBottom: 10,
  },
  actionText: {
    fontSize: 14,
    fontWeight: '600',
    color: '#2C3E50',
  },
  activityCard: {
    flexDirection: 'row',
    backgroundColor: '#FFF',
    padding: 15,
    borderRadius: 12,
    marginBottom: 10,
    elevation: 1,
    shadowColor: '#000',
    shadowOffset: { width: 0, height: 1 },
    shadowOpacity: 0.18,
    shadowRadius: 1.0,
  },
  activityIcon: {
    width: 40,
    height: 40,
    borderRadius: 10,
    backgroundColor: '#F5F6FA',
    justifyContent: 'center',
    alignItems: 'center',
    marginRight: 12,
  },
  activityContent: {
    flex: 1,
    justifyContent: 'center',
  },
  activityTitle: {
    fontSize: 15,
    fontWeight: '600',
    color: '#2C3E50',
  },
  activityTime: {
    fontSize: 13,
    color: '#95A5A6',
    marginTop: 2,
  },
})