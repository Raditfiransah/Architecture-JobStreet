<script setup>
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { Calendar, Info, MapPin } from 'lucide-vue-next';
import {
  Card,
  CardContent,
  CardDescription,
  CardHeader,
  CardTitle,
} from '@/Components/UI/ui/card';
import { Badge } from '@/Components/UI/ui/badge';

const props = defineProps({
  info: {
    type: Object,
    required: true,
  },
  featured: {
    type: Boolean,
    default: false,
  },
});

const badgeVariant = computed(() => {
  const variants = {
    Event: 'default',
    Magang: 'secondary',
    Sayembara: 'destructive',
  };

  return variants[props.info.kategori] ?? 'outline';
});

const formattedDate = computed(() => {
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric',
  }).format(new Date(props.info.created_at));
});

const posterSrc = computed(() => {
  if (props.info.image_url) {
    return props.info.image_url;
  }

  if (!props.info.gambar_poster) {
    return null;
  }

  if (props.info.gambar_poster.startsWith('http')) {
    return props.info.gambar_poster;
  }

  return `/storage/${props.info.gambar_poster}`;
});
</script>

<template>
  <Card
    :class="[
      'group overflow-hidden border-border/70 bg-card/95 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:border-primary/30 hover:shadow-md',
      featured ? 'md:grid md:grid-cols-[minmax(280px,0.95fr)_1.25fr]' : 'flex h-full flex-col',
    ]"
  >
    <Link
      :href="info.href || '#'"
      class="block overflow-hidden bg-muted"
      :class="featured ? 'aspect-video md:aspect-auto md:min-h-[280px]' : 'aspect-video'"
    >
      <img
        v-if="posterSrc"
        :src="posterSrc"
        :alt="`Poster ${info.judul}`"
        class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
      />
      <div v-else class="flex h-full w-full items-center justify-center bg-primary/5 text-primary">
        <Info class="h-12 w-12 opacity-30" />
      </div>
    </Link>

    <div class="flex min-w-0 flex-1 flex-col">
      <CardHeader :class="featured ? 'space-y-4 p-6 md:p-8' : 'space-y-3 p-5'">
        <div class="flex flex-wrap items-center gap-2">
          <Badge
            :variant="badgeVariant"
            class="w-fit text-[10px] font-bold uppercase tracking-wider"
          >
            {{ info.kategori }}
          </Badge>
          <span class="inline-flex items-center gap-1.5 text-xs text-muted-foreground">
            <Calendar class="h-3.5 w-3.5" />
            {{ formattedDate }}
          </span>
        </div>

        <div class="space-y-2">
          <CardTitle
            :class="[
              'font-bold leading-tight tracking-tight transition-colors group-hover:text-primary',
              featured ? 'text-2xl md:text-3xl' : 'text-xl',
            ]"
          >
            <Link :href="info.href || '#'" class="line-clamp-2">
              {{ info.judul }}
            </Link>
          </CardTitle>
          <CardDescription
            v-if="info.lokasi"
            class="inline-flex items-center gap-1.5 text-xs font-medium"
          >
            <MapPin class="h-3.5 w-3.5" />
            {{ info.lokasi }}
          </CardDescription>
        </div>
      </CardHeader>

      <CardContent :class="featured ? 'px-6 pb-6 md:px-8 md:pb-8' : 'flex-1 px-5 pb-5'">
        <p class="line-clamp-3 text-sm leading-relaxed text-muted-foreground">
          {{ info.deskripsi }}
        </p>
      </CardContent>
    </div>
  </Card>
</template>
